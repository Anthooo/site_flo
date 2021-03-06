<?php

namespace FloBundle\Controller;

use FloBundle\Entity\Cours;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Cour controller.
 *
 */
class CoursController extends Controller
{
    /**
     * Lists all cour entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cours = $em->getRepository('FloBundle:Cours')->findAll();

        return $this->render('@Flo/Admin/cours/index.html.twig', array(
            'cours' => $cours,
        ));
    }

    /**
     * Creates a new cour entity.
     *
     */
    public function newAction(Request $request)
    {
        $cour = new Cours();
        $form = $this->createForm('FloBundle\Form\CoursType', $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $cour->getImage()->upload($cour->getImage()->files);

            $em->persist($cour);
            $em->flush();

            return $this->redirectToRoute('cours_edit', array('id' => $cour->getId()));
        }

        return $this->render('@Flo/Admin/cours/new.html.twig', array(
            'cour' => $cour,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing cour entity.
     *
     */
    public function editAction(Request $request, Cours $cour)
    {
        $editForm = $this->createForm('FloBundle\Form\CoursType', $cour);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $cour->getImage()->upload($cour->getImage()->files);

            $em->flush();

            return $this->redirectToRoute('cours_edit', array('id' => $cour->getId()));
        }

        return $this->render('@Flo/Admin/cours/edit.html.twig', array(
            'cour' => $cour,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a cour entity.
     *
     */
    public function deleteAction($id)
    {
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $cours = $em->getRepository('FloBundle:Cours')->findOneBy(array('id' => $id));
            $image = $em->getRepository('FloBundle:Image')->findOneBy(array('id' => $cours->getImage()->getId()));
            $em->remove($cours);
            $cours->getImage()->removeUpload($image->getUrls());
            $em->flush();

            return $this->redirectToRoute('cours_index');
        } else
            return $this->redirectToRoute('cours_index');

    }

    /**
     * Deletes a image entity.
     *
     */
    public function deleteImgAction($id, $name)
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('FloBundle:Image')->findOneBy(array('id' => $id));

        $image->removeUpload($name);

        $em->flush();
        return $this->redirectToRoute('cours_index');
    }


}
