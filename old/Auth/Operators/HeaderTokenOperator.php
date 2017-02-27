<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Auth\Operators;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\Hashes\TokenHashes;
use Spiral\Auth\TokenInterface;
use Spiral\Core\FactoryInterface;

class HeaderTokenOperator extends AbstractTokenOperator
{
    /**
     * @var string
     */
    private $header;

    /**
     * @param FactoryInterface $factory
     * @param int              $lifetime
     * @param string           $sourceClass
     * @param TokenHashes      $hashes
     * @param string           $header Header to read token hash from.
     */
    public function __construct(
        FactoryInterface $factory,
        $lifetime,
        $sourceClass,
        TokenHashes $hashes,
        $header
    ) {
        parent::__construct($factory, $lifetime, $sourceClass, $hashes);

        $this->header = $header;
    }

    /**
     * {@inheritdoc}
     */
    public function mountToken(Request $request, Response $response, TokenInterface $token)
    {
        return $response->withAddedHeader($this->header, $token->getValue());
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken(Request $request, Response $response, TokenInterface $token)
    {
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    protected function extractHash(Request $request)
    {
        $header = $request->getHeaderLine($this->header);

        return !empty($header) ? $header : null;
    }
}