<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryDeleteTest extends WebTestCase
{
    protected static function createKernel(array $options = []): \Symfony\Component\HttpKernel\KernelInterface
    {
        // Ensure Kernel can be instantiated in this test environment
        return new \App\Kernel('test', true);
    }

    public function testDeleteCategoryRemovesEntity(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine')->getManager();

        // Create a category to delete
        $category = new Category();
        $category->setCategoryName('Delete Me');
        $category->setDescription('Test deletion');
        $em->persist($category);
        $em->flush();

        $id = $category->getId();
        $this->assertIsInt($id);

        // Fetch the homepage to get the rendered delete form and token
        $crawler = $client->request('GET', '/');
        $this->assertStringContainsString('Delete Me', $client->getResponse()->getContent());
        $token = $crawler->filter('form[action="/category/' . $id . '"] input[name="_token"]')->attr('value');

        // Submit the delete POST
        $client->request('POST', '/category/' . $id, ['_token' => $token]);

        // Expect redirect to homepage
        $this->assertResponseRedirects('/');

        // Ensure the entity was removed from DB
        $em->clear();
        $deleted = $em->getRepository(Category::class)->find($id);
        $this->assertNull($deleted, 'Category should have been deleted from the database.');
    }
}
