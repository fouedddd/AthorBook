<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function searchbyid($id){
        return $this->createQueryBuilder('b')
         ->where('b.id =:id')
        ->setParameter('id',$id)
        ->getQuery()
        ->getResult();

    } 
 
    public function findBooksOrderByAuthorName(){
        return $this->createQueryBuilder('b')
        ->leftJoin('b.author','a')
        ->addSelect('a')
        ->orderBy('a.username','ASC')
        ->getQuery()
        ->getResult();
    }
    public function findBooksByYear()
    {
        return $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->where('b.publicationdate > :year')
            ->andWhere('a.nbrbook > :bookCount')
            ->setParameters([
                'year' => new \DateTime('2018-01-01'), 
                'bookCount' => 35,
            ])
            ->getQuery()
            ->getResult();
    }
    
    
    
    //////avec dql
    public function findbooksbyCategorie(){
        $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery(
        'SELECT b
        FROM App\Entity\Book b
        WHERE b.category = :category
        ORDER BY b.id ASC'
    );
    $query->setParameter('category', 'Science Fiction'); 
    return $query->getResult();


    }
    public function findbookpubliched(){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT b
            FROM App\Entity\Book b
            WHERE b.publicationdate > :date1 AND b.publicationdate < :date2
            ORDER BY b.id ASC'
        );
        $query->setParameter('date1', '2014-01-01');
        $query->setParameter('date2', '2018-12-31');
        
        return $query->getResult();
    }
}

        

    


//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

