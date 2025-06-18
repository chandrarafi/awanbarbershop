<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Awan Barbershop - Tempat Cukur Rambut Terbaik</title>
    <!-- Include jQuery first -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <?= $this->include('templates/style') ?>
    <?= $this->renderSection('custom_css') ?>
</head>

<body class="bg-gradient-to-b from-[#2C3E50] to-[#ECF0F1]">
    <?= $this->include('templates/navbar') ?>

    <?= $this->renderSection('content') ?>

    <?= $this->include('templates/footer') ?>

    <?= $this->include('templates/script') ?>
    <?= $this->renderSection('custom_script') ?>

</body>

</html>