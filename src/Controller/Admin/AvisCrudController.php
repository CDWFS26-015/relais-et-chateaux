<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;

class AvisCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Avis::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Avis')
            ->setEntityLabelInPlural('Avis')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('note'),
            TextareaField::new('commentaire'),
            BooleanField::new('accepte'),
            DateTimeField::new('createdAt'),
            DateTimeField::new('updatedAt'),
        ];
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $user = $this->getUser();

        if (!$user) {
            return $qb;
        }

        if (!in_array('ROLE_ADMIN', $user->getRoles()) && in_array('ROLE_RESPONSABLE', $user->getRoles())) {
            // Responsable voit seulement les avis de ses événements
            $qb->join('entity.evenement', 'e')
               ->andWhere('e.responsable = :user')
               ->setParameter('user', $user);
        } elseif (!in_array('ROLE_ADMIN', $user->getRoles())) {
            // Utilisateur standard ne voit que ses propres avis
            $qb->andWhere('entity.utilisateur = :user')
               ->setParameter('user', $user);
        }

        return $qb;
    }
}
