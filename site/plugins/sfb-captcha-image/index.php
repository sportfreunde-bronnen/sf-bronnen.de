<?php

use SimpleCaptcha\Builder;

Kirby::plugin('sfb/captcha-image', [
    'routes' => [
        [
            'pattern' => 'captcha/img',
            'action'  => function () {

                header('Content-Type: image/png');

                $phrase = Builder::buildPhrase(5, 'ABEGSB8374');

                kirby()->session()->set('cPhrase', $phrase);

                $builder = new Builder($phrase);

                $builder->bgColor = '#004869';
                $builder->textColor = '#ffffff';
                $builder->applyPostEffects = false;
                $builder->build();


                $builder->output(1, 'png');
            }
        ]
    ],
    'validators' => [
        'validCaptcha' => function ($value) {
            return Builder::create(kirby()->session()->get('cPhrase') ?? null)
                ->compare($value);
        }
    ]
]);