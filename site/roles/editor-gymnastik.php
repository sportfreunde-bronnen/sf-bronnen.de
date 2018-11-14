<?php
return [
    'name'        => 'Editor-Gymnastik',
    'default'     => false,
    'permissions' => [
        '*' => true,
        'panel.user.update' => function() {
            return ($this->user()->is($this->target()->user()));
        },
        'panel.page.read' => function() {
            if ($this->target()->page()->title() == 'api') {
                return false;
            }
            return (
                $this->target()->page()->title() == 'Gymnastik' ||
                $this->target()->page()->parents()->last()->title() == 'Gymnastik'
            );
        }
    ]
];