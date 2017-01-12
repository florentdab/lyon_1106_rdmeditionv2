<?php

namespace AppBundle\Entity;

use AppBundle\AppBundle;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Message\Request;
use  Sylius\Component\Core\Model\Product as BaseProduct;
use Sylius\Component\Product\Model\ProductTranslation;
//use Sylius\Component\Core\Model\ProductTranslation;

class Product extends BaseProduct
{

    /*
     * LifeCycleCallBack Events
     */
    protected $data = [];

    public function setCreatedAtValue(LifecycleEventArgs $args)
    {

        $this->createdAt = new \DateTime();
        $em = $args->getObjectManager()->getRepository(Product::class)->findAll();

        if ($this->getPreorder() == true) {

            foreach ($em as $book) {
                if ($book->getPreorder() == true) {
                    $book->setPreorder(false);
                    $this->data[] = $book;
                }
            }
        } elseif ($this->getNew() == true) {

            foreach ($em as $book) {
                if ($book->getNew() == true) {
                    $book->setNew(false);
                    $this->data[] = $book;
                }
            }
        } elseif ($this->getPromo() == true) {

            foreach ($em as $book) {
                if ($book->getPromo() == true) {
                    $book->setPromo(false);
                    $this->data[] = $book;
                }
            }
        }
    }

    public function setUpdatedAtValue(LifecycleEventArgs $args)
    {
        $id = $this->getId();
        $this->updatedAt = new \DateTime();
        $em = $args->getObjectManager()->getRepository(Product::class)->findAll();
        if ($this->getPreorder() == true) {
            foreach ($em as $book) {
                if ($book->getPreorder() == true && $book->getId() != $id) {
                    $book->setPreorder(false);
                    $this->data[] = $book;
                }
            }
        } elseif ($this->getNew() == true ) {
            foreach ($em as $book) {
                if ($book->getNew() == true && $book->getId() != $id) {
                    $book->setNew(false);
                    $this->data[] = $book;
                }
            }
        } elseif ($this->getPromo() == true) {
            foreach ($em as $book) {
                if ($book->getPromo() == true && $book->getId() != $id) {
                    $book->setPromo(false);
                    $this->data[] = $book;
                }
            }
        }
    }

    public function postFlush(LifecycleEventArgs $args)
    {
        if(!empty($this->data)) {

            $em = $args->getEntityManager();
            foreach ($this->data as $book) {
                $em->persist($book);
            }
            $this->data = [];
            $em->flush();
        }
    }

    /*
        Fields added on Product model
     */
    private $author;

    private $preorder;

    private $new;

    private $promo;

    private $releaseDate;
    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }


    /**
     * @return mixed
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * @param mixed $promo
     */
    public function setPromo($promo)
    {
        $this->promo = $promo;
    }

    /**
     * @return mixed
     */
    public function getPreorder()
    {
        return $this->preorder;
    }

    /**
     * @param mixed $preorder
     */
    public function setPreorder($preorder)
    {
        $this->preorder = $preorder;
    }

    /**
     * @return mixed
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * @param mixed $new
     */
    public function setNew($new)
    {
        $this->new = $new;
    }

    /**
     * @return mixed
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * @param mixed $releaseDate
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;
    }


    /**
     * @var string
     */
    private $condition;


    /**
     * Set condition
     *
     * @param string $condition
     * @return Product
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get condition
     *
     * @return string 
     */
    public function getCondition()
    {
        return $this->condition;
    }
}
