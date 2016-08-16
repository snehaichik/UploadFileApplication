<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\UploadFileType;
use AppBundle\Uploader\FileUpload;
use AppBundle\Entity\Files;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(UploadFileType::class);
        $form->handleRequest($request);

        $file = new Files();

        if ($form->isSubmitted()) {

            $url =  $this->get('app.file_upload')->upload($form['file']->getData());

            $file->setFileName($url);

            $em = $this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }
}
