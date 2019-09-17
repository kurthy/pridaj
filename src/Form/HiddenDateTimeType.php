<?php
// src/Form/HiddenDateTimeType.php
namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class HiddenDateTimeType extends HiddenType implements DataTransformerInterface
{
    /**
    * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    /**
    * {@inheritdoc}
    */
    public function transform($data)
    {
        return $data->format("Y-m-d H:i:s");
    }

    /**
    * {@inheritdoc}
    */
    public function reverseTransform($data)
    {
        try {
            return new \DateTime($data);
        } catch (\Exception $e) {
            throw new TransformationFailedException($e->getMessage());
        }
    }

    public function getName()
    {
        return 'hidden_datetime';
    }
}

?>
