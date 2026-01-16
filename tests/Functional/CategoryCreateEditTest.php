<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryCreateEditTest extends WebTestCase
{
    protected static function createKernel(array $options = []): \Symfony\Component\HttpKernel\KernelInterface
    {
        return new \App\Kernel('test', true);
    }

    public function testCreateCategoryCreatesEntityAndShowsFlash(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        // Router exists and category_new route can be generated
        $router = $container->get('router');
        $url = $router->generate('category_new');
        $this->assertStringContainsString('/category/new', $url);

        // Request the new form
        $crawler = $client->request('GET', '/category/new');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Create')->form([
            'category[categoryName]' => 'Created Cat',
            'category[description]' => 'Created by test',
        ]);

        $client->submit($form);

        // Expect redirect to homepage and flash message
        $this->assertResponseRedirects('/');
        $client->followRedirect();
        $this->assertStringContainsString('Category created.', $client->getResponse()->getContent());

        // Ensure the category exists in DB
        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine')->getManager();
        $cat = $em->getRepository(Category::class)->findOneBy(['categoryName' => 'Created Cat']);
        $this->assertNotNull($cat);

        // Reload the homepage and assert the created name appears in the page HTML
        $client->request('GET', '/');
        $this->assertStringContainsString('Created Cat', $client->getResponse()->getContent());
        $this->assertStringContainsString('Created by test', $client->getResponse()->getContent());
    }

    public function testEditCategoryUpdatesEntityAndShowsFlash(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine')->getManager();

        // Create a category to edit
        $category = new Category();
        $category->setCategoryName('To Edit');
        $category->setDescription('Before');
        $em->persist($category);
        $em->flush();

        $id = $category->getId();

        // Request the edit form
        $crawler = $client->request('GET', '/category/' . $id . '/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Update')->form([
            'category[categoryName]' => 'Edited Name',
            'category[description]' => 'After',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/');
        $client->followRedirect();
        $this->assertStringContainsString('Category updated.', $client->getResponse()->getContent());

        // Ensure the category was updated
        $em->clear();
        $updated = $em->getRepository(Category::class)->find($id);
        $this->assertSame('Edited Name', $updated->getCategoryName());
        $this->assertSame('After', $updated->getDescription());
    }
}
