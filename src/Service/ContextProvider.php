<?php

namespace App\Service;

use App\Dto\Menu\Entry;
use App\Dto\Menu\Menu;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class ContextProvider
{
    private Menu $menu;

    public function __construct(private Security $security)
    {
        $this->security = $security;
        $this->menu = new Menu();
        $this->configureMenuEntries();
    }

    private function configureMenuEntries()
    {
        if (!$this->security->getUser()) {
            return;
        }

        if (\in_array(User::ROLE_ADMIN, $this->security->getUser()->getRoles())) {
            $this->menu
                ->add(new Entry('Badges', 'achievement_list'));
        } else {
            $this->menu
                ->add(new Entry('Donner un point', 'badge_give'))
                ->add(new Entry('Mes Badges', 'achievement_list'));
        }

        $this->menu
            ->add(new Entry('Utilisateurs', 'user_list'))
            ;
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }
}
