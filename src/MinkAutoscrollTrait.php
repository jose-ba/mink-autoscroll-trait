<?php

declare(strict_types=1);

namespace JoseBa\MinkAutoscrollTrait;

use Behat\Mink\Session;

/**
 * Trait para Selenium2Driver para que haga scroll automáticamente cuando un elemento está fuera del viewport.
 */
trait MinkAutoscrollTrait
{
    public function scrollToElement(string $cssSelector, Session $session, int $timeout)
    {
        $start = time();
        while (time() - $start < $timeout) {
            $session->executeScript("document.querySelector('$cssSelector').scrollIntoView({
                behavior: 'auto',
                block: 'center',
                inline: 'center'
            });");

            $elementIsInViewport = $session->evaluateScript("
                return document.querySelector('$cssSelector').getBoundingClientRect().top < window.innerHeight &&
                       document.querySelector('$cssSelector').getBoundingClientRect().bottom >= 0;
            ");

            if ($elementIsInViewport) {
                break;
            }
            sleep(1);
        }
    }
}
