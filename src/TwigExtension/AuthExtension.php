<?php

/*
 * @author  Xavier Chopin <xavier.chopin@univ-lorraine.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\TwigExtension;

use App\Event\AdminSubscriber;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;
use Twig_SimpleFunction;

class AuthExtension extends Twig_Extension
{
    /**
     *
     * @var resource LDAP Instance
     */
    protected $ldap;

    /**
     * @var string Base Distinguished Name
     */
    protected $baseDN;

    /**
     * @var bool
     */
    protected $isAdmin;

    /**
     * AuthExtension constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $adminSubscriber = new AdminSubscriber($container);
        $this->isAdmin = $adminSubscriber->isAdmin();
    }

    public function getName()
    {
        return 'auth';
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('auth', [$this, 'auth'])
        ];
    }

    public function auth()
    {
        $name = null; $logged = false; $isAdmin = false; $username = null; $email = null; $viewAsMode = false;
        $originalUsername = null;

        if (isset($_SESSION['phpCAS']['user'])) {
            $logged = true;
            $username = $_SESSION['phpCAS']['user'];
            $name = $_SESSION['name'];
            $email = $_SESSION['email'];
            $isAdmin = $this->isAdmin;

            if (isset($_SESSION['username'])) {
                $viewAsMode = true;
                $originalUsername = $_SESSION['username'];
            }
        }

        return (object)
        [
            'isLogged' => $logged,
            'isAdmin' =>  $isAdmin,
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'viewAsMode' => $viewAsMode,
            'originalUsername' => $originalUsername
        ];
    }
}
