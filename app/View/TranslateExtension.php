<?php

namespace App\View;

use Symfony\Component\Translation\TranslatorInterface as Translator;

class TranslateExtension extends \Twig_Extension
{
    protected $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('trans', [$this, 'trans']),
            new \Twig_SimpleFunction('trans_choice', [$this, 'transChoice']),
        ];
    }

    /**
     * Usage: {{ trans('common.welcome', { '%name%' : 'Alex' }) }}
     */
    public function trans($key, $parameters = [])
    {
        return $this->translator->trans($key, $parameters);
    }

    /**
     * Usage: {{ trans_choice('messages.new_messages', 5, { '%name%' : 'Alex' }) }}
     */
    public function transChoice($key, $count = 1, $parameters = [])
    {
        return $this->translator->transChoice($key, $count, $parameters);
    }
}
