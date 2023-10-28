<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Form\SearchauthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use PharIo\Manifest\ManifestLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    public $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/showauthor/{name}', name: 'app_showauthor')]
    public function showauthor($name): Response
    {
        return $this->render('author/showauthor.html.twig', [
           'name'=>$name
        ]);
    }
    #[Route('/showtableauthor', name: 'app_showtableauthor')]
    public function showtableauthor(): Response
    {
       
        return $this->render('author/table.html.twig', [
            'author' => $this->authors,
        ]);
    }
    #[Route('/showbyId{id}', name: 'showbyId')]
    public function showbyId($id): Response
    {

        $author= null;
        foreach ($this->authors as $authorD) {
            if ($authorD['id'] == $id){
                $author = $authorD;
            }
        }
       // var_dump($author).die();


        return $this->render('author/showbyId.html.twig', [
            'author' => $author
        ]);
    }
    #[Route('/showDB', name: 'showDB')]
    public function showDB(AuthorRepository $x): Response
    {
        //$author = $x->findAll();
        $author = $x->trisbyemail();
       
        return $this->render('author/showDB.html.twig', [
            'author' => $author
        ]);
    } 
    #[Route('/Addauthor', name: 'Addauthor')]
    public function Addauthor(ManagerRegistry $managerRegistry): Response
    {
        $em=$managerRegistry->getManager();
        $author=new Author();
        $author->setUsername("3A55");
        $author->setEmail("3A55@esprit.tn");
        $em->persist($author);
        $em->flush();
        return new Response("great add");
    }
    #[Route('/addform', name: 'addform')]
    public function addform(ManagerRegistry $managerRegistry,request $req): Response
    {
        $em=$managerRegistry->getManager();
        $author= new Author();
        $form=$this->createForm(AuthorType::class, $author);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
        $em->persist($author);
        $em->flush();
        return $this->redirect('showDB');
    }


        return $this->renderForm('author/addform.html.twig', [
            'f'=>$form
        ]);
    }
    #[Route('/Editauthor{id}', name: 'Editauthor')]
    public function Editauthor($id,ManagerRegistry $managerRegistry,request $req,AuthorRepository $authorRepository): Response
    {
        //var_dump($id).die();
        $em=$managerRegistry->getManager();
        $dataid=$authorRepository->find($id);
        //var_dump($dataid).die();
        $form=$this->createForm(AuthorType::class,$dataid);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $em->persist($dataid);
            $em->flush();
            return $this->redirect('showDB');
        }

        return $this->renderForm('author/Editauthor.html.twig', [
            'form'=>$form
        ]);
    }
    #[Route('/Deleteauthor{id}', name: 'Deleteauthor')]
    public function Deleteauthor($id,ManagerRegistry $managerRegistry,AuthorRepository $authorRepository): Response
    {
        $em=$managerRegistry->getManager();
        $dataid=$authorRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirect('showDB');

        
       
    }
    #[Route('/searchbyNbrbook', name: 'searchbyNbrbook')]
        public function searchbyNbrbook(AuthorRepository $authorRepository,Request $req): Response
        {
            $form=$this->createForm(SearchauthorType::class);
            $form->handleRequest($req);
            $min = $form->get('MinNumber')->getData();
            $max = $form->get('MaxNumber')->getData();
            $author=$authorRepository->findchbynumberbook($min,$max);
            return $this->renderForm('author/searchbyNbrbook.html.twig', [
                'author' => $author,
                'f'=>$form
            ]);
        }
        #[Route('/deleteNbrzero', name: 'deleteNbrzero')]
        public function deleteNbrzero(AuthorRepository $authorRepository): Response
        {
            $author=$authorRepository->deleteNbrzero();
            return $this->redirect('showDB');
        }
    

    

}
