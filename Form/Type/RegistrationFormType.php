<?php

namespace HarvestCloud\CoreBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

/**
 * RegistrationFormType to override default FOSUserBundle form
 *
 * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
 * @since  2012-04-07
 */
class RegistrationFormType extends BaseType
{
    /**
     * buildForm
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @param  FormBuilder  $builder
     * @param  array        $options
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // remove username field since we will be using the email address
        $builder->remove('username');

        // Add custom fields
        $builder->add('firstname');
        $builder->add('lastname');
        $builder->add('postal_code');
    }

    /**
     * buildForm
     *
     * @author Tom Haskins-Vaughan <tom@harvestcloud.com>
     * @since  2012-04-07
     *
     * @return string
     */
    public function getName()
    {
        return 'harvestcloud_user_registration';
    }
}
