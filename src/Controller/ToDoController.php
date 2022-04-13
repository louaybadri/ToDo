<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

class ToDoController extends AbstractController
{
    #[Route('/todo', name: 'todo')]
    public function index(SessionInterface $session): Response
    {
        if(!$session->has('todos')){
            $todos =[
                'dimanche'=>'activite1',
                'lundi'=>'activite2',
                'mardi'=>'activite3',
                'mercredi'=>'activite4',
                'jeudi'=>'activite5',
            ];
            $session->set('todos',$todos);
            $this->addFlash('info',"Welcome to your TODO list");

        }
        return $this->render('to_do/index.html.twig');
    }

    #[Route('/todo/add/{cle}/{valeur}', name: 'addToDo')]
    public function addToDo($cle,$valeur,SessionInterface $session){
        if(!$session->has('todos')){
            $this->addFlash('error',"La liste est vide");
        }else{
            $todos=$session->get('todos');
            if(isset($todos[$cle])){
                $this->addFlash('error',"le ToDo $cle deja existe");
            }else{
                $todos[$cle]=$valeur;
                $session->set('todos',$todos);
                $this->addFlash('success',"le ToDo $cle est ajoute avec succes");

            }
        }
        return $this->redirectToRoute('todo');

    }
    #[Route('/todo/remove/{cle}/{valeur}', name: 'removeToDo')]
    public function removeToDo($cle,$valeur,SessionInterface $session){
        if(!$session->has('todos')){
            $this->addFlash('error',"La liste est vide");
        }else{
            $todos=$session->get('todos');
            if(isset($todos[$cle])){
                unset($todos[$cle]);
                $this->addFlash('success',"le ToDo $cle est supprime avec succes");
                $session->set('todos',$todos);

            }else{
                $this->addFlash('error',"le ToDo $cle n'existe pas deja");

            }
        }
        return $this->redirectToRoute('todo');

    }
    #[Route('/todo/reset', name: 'resetToDo')]
    public function resetToDo(SessionInterface $session){
        $todos =[
            'dimanche'=>'activite1',
            'lundi'=>'activite2',
            'mardi'=>'activite3',
            'mercredi'=>'activite4',
            'jeudi'=>'activite5',
        ];
        $this->addFlash('success',"le ToDo est remis a l'etat initiale");
        $session->set('todos',$todos);
        return $this->redirectToRoute('todo');

    }

}
