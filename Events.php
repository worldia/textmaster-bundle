<?php

namespace Worldia\Bundle\TextmasterBundle;

class Events
{
    /**
     * Triggered when project is launched and is ready to be taken by authors.
     * Useful for asynchronous launching.
     */
    const PROJECT_IN_PROGRESS = 'callback.project.in_progress';

    /**
     * Under review.
     *
     * The document is ready for review by the client.
     */
    const DOCUMENT_IN_REVIEW = 'callback.document.in_review';

    /**
     * Completed.
     *
     * The job is completed and the author is paid.
     */
    const DOCUMENT_COMPLETED = 'callback.document.completed';
}
