<?php

namespace App\Form;

use App\Entity\Entry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		if($options['id'] == 0){
			$builder->add('type', ChoiceType::class, [
				'choices'  => [
					'File' => true,
					'Text' => true
				],
			])
            ->add('entry_val',FileType::class, array('data_class' => null))
			->add('status', HiddenType::class, ['data' => $options['status']])
			->add('posted_by', HiddenType::class, ['data' => $options['user_id']]);
		}else{
			$builder
            ->add('type', ChoiceType::class, [
				'choices'  => [
					'File' => true,
					'Text' => true
				],
			])
			->add('status', ChoiceType::class, [
				'choices'  => [
					'Approve' => 'approved',
					'Unapprove' => 'unapproved'
				]
			])
			->add('posted_by', HiddenType::class, ['data' => $options['user_id']]);
		}
		
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entry::class,
			'user_id' => 0,
			'status' => 'unapproved',
			'id' => 0
        ]);
    }
}
