<?php

namespace App\Controller;

use App\Entity\Entry;
use App\Form\EntryType;
use App\Repository\EntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/entry')]
class EntryController extends AbstractController
{
    #[Route('/', name: 'app_entry_index', methods: ['GET'])]
    public function index(EntryRepository $entryRepository): Response
    {
        return $this->render('entry/index.html.twig', [
            'entries' => $entryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_entry_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntryRepository $entryRepository): Response
    {
        $entry = new Entry();
		$user = $this->getUser();
		$user_id = $user->getId();
        $form = $this->createForm(EntryType::class, $entry,['user_id' => $user_id]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entryRepository->save($entry, true);

            return $this->redirectToRoute('app_entry_index', [], Response::HTTP_SEE_OTHER);
        }
		
        return $this->renderForm('entry/new.html.twig', [
            'entry' => $entry,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'app_entry_show', methods: ['GET'])]
    public function show(Entry $entry): Response
    {
        return $this->render('entry/show.html.twig', [
            'entry' => $entry,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_entry_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entry $entry, EntryRepository $entryRepository): Response
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
		$id = $entry->getId();
		$posted_by = $entry->getPostedBy();
        $form = $this->createForm(EntryType::class, $entry,['id' => $id,'user_id' => $posted_by]);
		$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entryRepository->save($entry, true);

            return $this->redirectToRoute('app_entry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entry/edit.html.twig', [
            'entry' => $entry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entry_delete', methods: ['POST'])]
    public function delete(Request $request, Entry $entry, EntryRepository $entryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entry->getId(), $request->request->get('_token'))) {
            $entryRepository->remove($entry, true);
        }

        return $this->redirectToRoute('app_entry_index', [], Response::HTTP_SEE_OTHER);
    }
}
