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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AuthExtension extends AbstractExtension
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

    private $session;

    /**
     * AuthExtension constructor.
     *
     * @param ContainerInterface $container
     * @param RequestStack $request_stack
     */
    public function __construct(ContainerInterface $container, RequestStack $request_stack)
    {

        $this->session = $request_stack->getCurrentRequest()->getSession();
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
            new TwigFunction('auth', [$this, 'auth'])
        ];
    }

    public function auth()
    {

        $logged = false;
        $isAdmin = false;
        $viewAsMode = false;
        $professorMode = false;
        $username = null;
        $email = null;
        $name = null;

        $session = $this->session;


        if (isset($_SESSION['phpCAS']['user'])) {
            $logged = true;
            $username = $_SESSION['phpCAS']['user'];
            $name = $_SESSION['name'];
            $email = $_SESSION['email'];
            $isAdmin = $_SESSION['isAdmin'];
            $viewAsMode = isset($_SESSION['originalUsername']); // if this variable exists, it's in view as mode
            $professorMode = isset($_SESSION['professorMode']);
        }

        return (object)
        [
            'isLogged' => $logged,
            'isAdmin' => $isAdmin,
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'viewAsMode' => $viewAsMode,
            'professorMode' => $professorMode
        ];
    }
}
