<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        Article::create([
            'title' => 'Introduction to Laravel 12',
            'content' => 'Laravel is a modern PHP framework designed to make web application development simple, elegant, and productive.',
            'author' => 'Admin',
        ]);

        Article::create([
            'title' => 'Understanding Docker',
            'content' => 'Docker provides a standardized way to package applications and their dependencies into portable containers.',
            'author' => 'Admin',
        ]);

        Article::create([
            'title' => 'Kubernetes Fundamentals',
            'content' => 'Kubernetes is a container orchestration platform used to deploy, scale, and manage containerized applications.',
            'author' => 'John Doe',
        ]);

        Article::create([
            'title' => 'Introduction to Linux',
            'content' => 'Linux is widely used for servers, cloud infrastructure, containers, networking, and DevOps environments.',
            'author' => 'Jane Smith',
        ]);

        Article::create([
            'title' => 'Getting Started with MySQL',
            'content' => 'MySQL is a popular relational database management system used by many web applications.',
            'author' => 'Admin',
        ]);

        Article::create([
            'title' => 'Understanding REST APIs',
            'content' => 'REST APIs allow applications to communicate using standard HTTP methods such as GET, POST, PUT, and DELETE.',
            'author' => 'John Doe',
        ]);

        Article::create([
            'title' => 'What is Model Context Protocol?',
            'content' => 'Model Context Protocol provides a standardized way for AI applications to interact with external tools, services, and data.',
            'author' => 'Admin',
        ]);

        Article::create([
            'title' => 'Building AI Applications with Local LLMs',
            'content' => 'Local language models allow developers to build AI applications without sending application data to external AI providers.',
            'author' => 'Jane Smith',
        ]);

        Article::create([
            'title' => 'Laravel Service Layer Pattern',
            'content' => 'A service layer can help separate application and business logic from controllers and make the application easier to maintain.',
            'author' => 'Admin',
        ]);

        Article::create([
            'title' => 'Introduction to DevOps',
            'content' => 'DevOps combines development and operations practices to improve software delivery, automation, reliability, and collaboration.',
            'author' => 'John Doe',
        ]);
    }
}