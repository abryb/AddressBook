<?php

namespace AddressBookBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $contact = $builder->getData();
        $userId = $contact->getUser()->getId();

        $builder
            ->add('name')
            ->add('surname')
            ->add('description')
            ->add('groups',EntityType::class, [
                'class' => 'AddressBookBundle\Entity\ContactGroup',
                'choice_label' => 'name',
                'expanded' => 'true',
                'multiple' => 'true',
                'query_builder' => function (EntityRepository $er) use ($userId) {
                    return $er->createQueryBuilder('g')
                        ->where('g.user = ?1')
                        ->orderBy('g.name', 'ASC')
                        ->setParameter(1, $userId);
                }]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AddressBookBundle\Entity\Contact'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'addressbookbundle_contact';
    }


}
