<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Form\SearchType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/addbook', name: 'addbook')]
    public function addbook(ManagerRegistry $managerRegistry,Request $req): Response
    {
        $em=$managerRegistry->getManager();
        $book=new Book();
        $form=$this->createForm(BookType::class, $book);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
        $em->persist($book);
        $em->flush();
       // return $this->redirect('showDB');
    }
        return $this->renderForm('book/addbook.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route('/showbook', name: 'showbook')]
    public function showbook(BookRepository $bookRepository): Response
    {
        $book = $bookRepository->findAll();
       
        return $this->render('book/show.html.twig', [
            'book' => $book
        ]);
    }
    #[Route('/editbook{id}', name: 'editbook')]
    public function editbook($id,ManagerRegistry $managerRegistry,Request $req,BookRepository $bookRepository): Response
    {
        $em=$managerRegistry->getManager();
        $book=$bookRepository->find($id);
        $form=$this->createForm(BookType::class, $book);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
        $em->persist($book);
        $em->flush();
       // return $this->redirect('showDB');
    }
        return $this->renderForm('book/addbook.html.twig', [
            'f' => $form,
        ]);
    } 
    #[Route('/serachbook', name: 'serachbook')]
    public function serachbook(BookRepository $bookRepository,Request $req): Response
    {
    
        $form=$this->createForm(SearchType::class);
        $form->handleRequest($req);
        $result = $form->get('id')->getData();
        $book=$bookRepository->searchbyid($result);
        return $this->renderForm('book/serachbook.html.twig', [
            'b' => $book,
             'f'=> $form
        ]);
    }
    #[Route('/trisbynomauthor', name: 'trisbynomauthor')]
    public function trisbynomauthor(BookRepository $bookRepository,Request $req): Response
    {
    
       $book=$bookRepository->findBooksOrderByAuthorName();
        return $this->renderForm('book/trisbynomauthor.html.twig', [
            'b' => $book,
             
        ]);
    }
    #[Route('/showbyanne', name: 'showbyanne')]
    public function showbyanne(BookRepository $bookRepository,Request $req): Response
    {
    
       $book=$bookRepository->findBooksByYear();

        return $this->renderForm('book/showbyanne.html.twig', [
            'b' => $book,
             
        ]);
    }
    #[Route('/showbycategorie', name: 'showbycategorie')]
    public function showbycategorie(BookRepository $bookRepository,Request $req): Response
    {
    
       $book=$bookRepository->findbooksbyCategorie();

        return $this->renderForm('book/showbycategorie.html.twig', [
            'b' => $book,
             
        ]);
    }
    #[Route('/publichedbook', name: 'publichedbook')]
    public function publichedbook(BookRepository $bookRepository,Request $req): Response
    {
    
       $book=$bookRepository->findbookpubliched();

        return $this->renderForm('book/publichedbook.html.twig', [
            'b' => $book,
             
        ]);
    }
    
   
}
