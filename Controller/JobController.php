<?php

namespace Worldia\Bundle\TextmasterBundle\Controller;

use Doctrine\ORM\EntityManager;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Textmaster\Translator\TranslatorInterface;
use Worldia\Bundle\TextmasterBundle\Entity\JobInterface;
use Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface;

class JobController extends AbstractController
{
    protected $resource = 'job';

    /**
     * Use textmaster translator to make a comparison for the given job's document.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function compareAction(Request $request)
    {
        $job = $this->getResource($request);
        $document = $this->getJobManager()->getDocument($job);

        return $this->render(
            'compare',
            [
                'job' => $job,
                'document' => $document,
                'diffs' => $this->getTranslator()->compare($document),
            ]
        );
    }

    /**
     * Use textmaster translator to accept the given job's document.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function acceptAction(Request $request)
    {
        $job = $this->getResource($request);
        $document = $this->getJobManager()->getDocument($job);

        $form = $this->container->get('form.factory')->create('textmaster_job_validation');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->getTranslator()->complete($document, $data['satisfaction'], $data['message']);

            return $this->redirectAfterAccept($job);
        }

        return $this->render('accept', [
            'job' => $job,
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Reject the given job's document.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function rejectAction(Request $request)
    {
        $job = $this->getResource($request);
        $document = $this->getJobManager()->getDocument($job);

        $form = $this->container->get('form.factory')->create('textmaster_job_validation', null, ['accept' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $document->reject($data['message']);

            return $this->redirectAfterReject($job);
        }

        return $this->render('reject', [
            'job' => $job,
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Redirect after accepting a job.
     *
     * @param JobInterface $job
     */
    protected function redirectAfterAccept(JobInterface $job)
    {
        $this->redirect('worldia_textmaster_job_compare', ['id' => $job->getId()]);
    }

    /**
     * Redirect after rejecting a job.
     *
     * @param JobInterface $job
     */
    protected function redirectAfterReject(JobInterface $job)
    {
        $this->redirect('worldia_textmaster_job_compare', ['id' => $job->getId()]);
    }

    /**
     * @param Request $request
     *
     * @return Pagerfanta
     */
    protected function getResources(Request $request)
    {
        return $this->container->get('worldia.textmaster.repository.job')->createPaginator($this->getCriteria($request));
    }

    /**
     * @param Request $request
     *
     * @return JobInterface
     */
    protected function getResource(Request $request)
    {
        return $this->getEntityManager()->getRepository('WorldiaTextmasterBundle:Job')->find($request->get('id'));
    }

    /**
     * Get entity manager.
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->container->get('doctrine')->getManager();
    }

    /**
     * Get job manager.
     *
     * @return JobManagerInterface
     */
    protected function getJobManager()
    {
        return $this->container->get('worldia.textmaster.manager.job');
    }

    /**
     * Get textmaster translator.
     *
     * @return TranslatorInterface
     */
    protected function getTranslator()
    {
        return $this->container->get('worldia.textmaster.api.translator');
    }
}
