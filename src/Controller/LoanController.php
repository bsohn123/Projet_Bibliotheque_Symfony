<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Loan;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LoanController extends AbstractController
{
    #[Route('/my-loans', name: 'my_loans')]
    public function myLoans(LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $loans = $loanRepository->findBy([
            'user' => $this->getUser(),
            'returnedAt' => null,
        ], ['borrowedAt' => 'DESC']);

        return $this->render('loan/my_loans.html.twig', [
            'loans' => $loans,
        ]);
    }

    #[Route('/loan/borrow/{id}', name: 'loan_borrow', methods: ['POST'])]
    public function borrow(
        Book $book,
        LoanRepository $loanRepository,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($loanRepository->hasActiveLoanForBook($book)) {
            $this->addFlash('error', 'Ce livre est déjà emprunté.');
            return $this->redirectToRoute('book_index');
        }

        $loan = new Loan();
        $loan->setBook($book);
        $loan->setUser($this->getUser());
        $loan->setBorrowedAt(new \DateTimeImmutable());

        $em->persist($loan);
        $em->flush();

        $this->addFlash('success', 'Livre emprunté !');
        return $this->redirectToRoute('my_loans');
    }

    #[Route('/loan/return/{id}', name: 'loan_return', methods: ['POST'])]
    public function returnLoan(
        Loan $loan,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($loan->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $loan->setReturnedAt(new \DateTimeImmutable());
        $em->flush();

        $this->addFlash('success', 'Livre rendu.');
        return $this->redirectToRoute('my_loans');
    }
}
