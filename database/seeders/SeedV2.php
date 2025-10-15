<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SeedV2 extends Seeder
{
    /**
     * Run the database seeds.
     * Generated on 2025-10-15 15:24:18
     */
    public function run(): void
    {
        // Copier les images depuis le répertoire seeder vers storage/app/public
        $this->copyImages();
        
        // Insérer les pages
        $this->seedPages();
    }

    /**
     * Copie les images du seeder vers le storage public
     */
    protected function copyImages(): void
    {
        $seederImagesPath = database_path('seeders/images/seed_v2');
        $disk = Storage::disk('public');
        
        if (!File::exists($seederImagesPath)) {
            return;
        }

        $imageFiles = File::allFiles($seederImagesPath);
        
        foreach ($imageFiles as $file) {
            $fileName = $file->getFilename();
            $this->copyImageToStorage($fileName, $file->getPathname(), $disk);
        }
    }

    /**
     * Copie une image vers le bon répertoire de storage
     */
    protected function copyImageToStorage(string $fileName, string $sourcePath, $disk): void
    {
        // Mapping des fichiers vers leurs répertoires d'origine
        $imageMapping = array (
  'chatgpt-image-15-oct-2025-00-16-05-01K7KJMJ6WW306P7P6YNG5VPG0.webp' => 'images-bg/chatgpt-image-15-oct-2025-00-16-05-01K7KJMJ6WW306P7P6YNG5VPG0.webp',
  'actiing-accompagnement-en-art-therapiejpg-01K7KK1ANPAD40F2SJGRFFMYVY.webp' => 'image-photos/actiing-accompagnement-en-art-therapiejpg-01K7KK1ANPAD40F2SJGRFFMYVY.webp',
  'benefits-of-art-therapy-blog-image-01K7KK32F15Z545ZSDBFKSFEYM.webp' => 'image-photos/benefits-of-art-therapy-blog-image-01K7KK32F15Z545ZSDBFKSFEYM.webp',
);
        
        if (isset($imageMapping[$fileName])) {
            $targetPath = $imageMapping[$fileName];
            $targetDir = dirname($targetPath);
            
            // Créer le répertoire si nécessaire
            if (!$disk->exists($targetDir)) {
                $disk->makeDirectory($targetDir);
            }
            
            // Copier le fichier
            $disk->put($targetPath, File::get($sourcePath));
        }
    }

    /**
     * Insert les pages dans la base de données
     */
    protected function seedPages(): void
    {
        $pages = array (
  0 => 
  array (
    'titre' => 'Mentions légales',
    'meta_data' => NULL,
    'contents' => '[{"data":{"anchor":null,"block_id":"c844644b-e08f-441f-9616-68aba28bb7b5","html_texts":"<h1>Mentions l\\u00e9gales<\\/h1><h2>\\u00c9diteur du site<\\/h2><p>Le pr\\u00e9sent site est \\u00e9dit\\u00e9 par :<br><strong>Notilac<\\/strong><br>Repr\\u00e9sentant l\\u00e9gal : <strong>Charles Saint Olive<\\/strong><br>Adresse : 69160 Tassin, France<br>E-mail : <a target=\\"_blank\\" rel=\\"noopener noreferrer nofollow\\" href=\\"mailto:charles@notilac.fr\\">charles@notilac.fr<\\/a><\\/p><h2>H\\u00e9bergeur<\\/h2><p>L\\u2019h\\u00e9bergement du site est assur\\u00e9 par :<br><strong>DigitalOcean, LLC<\\/strong><br>Si\\u00e8ge social : 101 Avenue of the Americas, 10th Floor, New York, NY 10013, USA<br>Localisation du serveur : <strong>Allemagne<\\/strong><br>Site web : <a target=\\"_blank\\" rel=\\"noopener noreferrer nofollow\\" href=\\"https:\\/\\/www.digitalocean.com\\">https:\\/\\/www.digitalocean.com<\\/a><\\/p><h2>Propri\\u00e9t\\u00e9 intellectuelle<\\/h2><p>L\\u2019ensemble des contenus pr\\u00e9sents sur ce site (textes, images, graphismes, logos, vid\\u00e9os, etc.) est prot\\u00e9g\\u00e9 par le droit de la propri\\u00e9t\\u00e9 intellectuelle. Toute reproduction, repr\\u00e9sentation, modification ou exploitation, totale ou partielle, sans l\\u2019autorisation pr\\u00e9alable de l\\u2019\\u00e9diteur est interdite.<\\/p><h2>Donn\\u00e9es personnelles<\\/h2><p>Ce site ne collecte pas de cookies tiers.<br>Les informations \\u00e9ventuellement transmises via le formulaire de contact (si existant) sont utilis\\u00e9es uniquement pour r\\u00e9pondre aux demandes des utilisateurs. Elles ne sont ni revendues ni transmises \\u00e0 des tiers.<\\/p><p>Conform\\u00e9ment au R\\u00e8glement G\\u00e9n\\u00e9ral sur la Protection des Donn\\u00e9es (RGPD \\u2013 UE 2016\\/679) et \\u00e0 la loi Informatique et Libert\\u00e9s, vous disposez d\\u2019un droit d\\u2019acc\\u00e8s, de rectification et de suppression de vos donn\\u00e9es.<br>Vous pouvez exercer ces droits en \\u00e9crivant \\u00e0 : <a target=\\"_blank\\" rel=\\"noopener noreferrer nofollow\\" href=\\"mailto:charles@notilac.fr\\">charles@notilac.fr<\\/a>.<\\/p><h2>Responsabilit\\u00e9<\\/h2><p>L\\u2019\\u00e9diteur s\\u2019efforce d\\u2019assurer l\\u2019exactitude des informations diffus\\u00e9es sur ce site. Toutefois, il ne saurait \\u00eatre tenu responsable des erreurs ou omissions, ni d\\u2019un \\u00e9ventuel dysfonctionnement ou interruption de disponibilit\\u00e9 du site.<\\/p><p>L\\u2019utilisation des liens hypertextes peut conduire l\\u2019utilisateur vers d\\u2019autres sites sur lesquels l\\u2019\\u00e9diteur n\\u2019exerce aucun contr\\u00f4le et d\\u00e9cline toute responsabilit\\u00e9.<\\/p>","html_title":"<p><\\/p>","description":null,"image_right":false,"couche_blanc":"aucun","style_listes":"alternance","secondary_text":false,"background_image":null,"couleur_primaire":"secondary","direction_couleur":"aucun","afficher_separateur":false,"section_background_image":null},"type":"content"}]',
    'statics' => NULL,
    'key_word' => NULL,
    'slug' => 'mentions',
    'status' => 'published',
    'is_homepage' => false,
    'is_in_header' => false,
    'is_in_footer' => true,
    'has_form' => false,
    'meta_description' => NULL,
    'meta_keywords' => NULL,
    'order' => 5,
    'published_at' => '2025-10-03 10:27:33',
    'created_at' => '2025-10-03 08:31:08',
    'updated_at' => '2025-10-15 09:36:33',
  ),
  1 => 
  array (
    'titre' => 'Politique de confidentialité',
    'meta_data' => NULL,
    'contents' => '[{"data":{"anchor":null,"animate":true,"boutons":[],"block_id":"05c7bcea-d16b-4f21-83d6-5bd28b805e08","minH70vh":true,"is_hidden":false,"html_title":"<p><\\/p>","description":null,"couche_blanc":"bg-gradient-to-b from-white\\/30 to-white\\/70","background_mode":"image","couleur_primaire":"secondary","image_background":"images-bg\\/chatgpt-image-15-oct-2025-00-16-05-01K7KJMJ6WW306P7P6YNG5VPG0.webp","direction_couleur":"aucun"},"type":"hero"}]',
    'statics' => NULL,
    'key_word' => NULL,
    'slug' => 'configentialite',
    'status' => 'published',
    'is_homepage' => false,
    'is_in_header' => false,
    'is_in_footer' => true,
    'has_form' => false,
    'meta_description' => NULL,
    'meta_keywords' => NULL,
    'order' => 6,
    'published_at' => NULL,
    'created_at' => '2025-10-03 08:49:19',
    'updated_at' => '2025-10-15 09:36:33',
  ),
  2 => 
  array (
    'titre' => 'Mon profil',
    'meta_data' => NULL,
    'contents' => '[{"data":{"anchor":null,"animate":false,"block_id":"0135f0ea-52d6-4a43-9147-7ca0624c7aff","minH70vh":false,"is_hidden":false,"html_texts":"<p><strong>Nom :<\\/strong> Claire Duval<br><strong>Titre professionnel :<\\/strong> Art-th\\u00e9rapeute dipl\\u00f4m\\u00e9e, m\\u00e9diatrice artistique<br><strong>Parcours et approche :<\\/strong><br>Claire Duval a \\u00e9tudi\\u00e9 les arts plastiques dans une \\u00e9cole d\\u2019art, puis s\\u2019est form\\u00e9e \\u00e0 l\\u2019art-th\\u00e9rapie (formation agr\\u00e9\\u00e9e de trois ans) et \\u00e0 la psychologie de l\\u2019expression. Elle enrichit sa pratique par une sensibilit\\u00e9 aux mat\\u00e9riaux (argile, collage, peinture, mat\\u00e9riaux recycl\\u00e9s).<\\/p><p>Elle travaille en lib\\u00e9ral, dans un atelier baign\\u00e9 de lumi\\u00e8re, tout en intervenant occasionnellement en institutions (maisons de retraite, centres de r\\u00e9\\u00e9ducation). Claire adopte une posture empathique, non jugeante et s\\u00e9curisante : elle propose des consignes souples, invite \\u00e0 l\\u2019exploration libre, et accompagne chaque personne \\u00e0 son rythme.<\\/p><p>Dans sa pratique, elle peut proposer des th\\u00e9matiques (ex. \\u00ab racines \\/ enracinement \\u00bb, \\u00ab m\\u00e9tamorphose \\u00bb) ou laisser une cr\\u00e9ation libre, selon les besoins. Elle accorde une place centrale au dialogue autour de l\\u2019\\u0153uvre produite, pour aider la personne \\u00e0 faire des liens symboliques \\u00e0 sa vie.<\\/p>","html_title":"<h2>Portrait fictif d\\u2019un praticien<\\/h2>","left_image":false,"description":null,"photo_config":{"image_url":null,"display_type":null},"style_listes":"alternance","background_datas":{"mode":"aucun"},"couleur_primaire":"secondary","afficher_separateur":true},"type":"content"}]',
    'statics' => NULL,
    'key_word' => NULL,
    'slug' => 'profil',
    'status' => 'published',
    'is_homepage' => false,
    'is_in_header' => true,
    'is_in_footer' => false,
    'has_form' => true,
    'meta_description' => NULL,
    'meta_keywords' => NULL,
    'order' => 3,
    'published_at' => '2025-10-03 10:51:11',
    'created_at' => '2025-10-03 08:51:18',
    'updated_at' => '2025-10-15 09:37:25',
  ),
  3 => 
  array (
    'titre' => 'Accueil',
    'meta_data' => NULL,
    'contents' => '[{"data":{"anchor":null,"boutons":[{"ancre":"#decouvrir","texte":"D\\u00e9couvrir l\'art-th\\u00e9rapie","couleur":"primary","page_id":"same_page","type_lien":"page"},{"ancre":"#contact","texte":"Prendre rendez-vous","couleur":"secondary","page_id":"same_page","type_lien":"page"}],"ambiance":{"animate":true,"minH70vh":true,"is_hidden":false,"couleur_primaire":"primary"},"block_id":"5bcc2627-d398-42b3-a03c-96f821856fa0","html_title":"<p><strong>Laetitia Deletraz<\\/strong><\\/p><p>Art-th\\u00e9rapeute<\\/p>","description":"L\\u2019art-th\\u00e9rapie est une d\\u00e9marche d\\u2019accompagnement par la cr\\u00e9ation artistique, visant \\u00e0 explorer ses \\u00e9motions, soulager des souffrances psychiques ou favoriser un mieux-\\u00eatre global. \\nElle s\\u2019adresse \\u00e0 tous, sans besoin de comp\\u00e9tences artistiques, et repose sur la relation entre le\\u2019individu, le mat\\u00e9riau et le praticien.","background_datas":{"mask":"hero-mask-1","mode":"filtre","mask_color":"bg-secondary-500"}},"type":"hero"},{"data":{"anchor":"decouvrir","ambiance":{"animate":true,"minH70vh":false,"is_hidden":false,"style_listes":"alternance","couleur_primaire":"primary","afficher_separateur":true},"block_id":"e9c6b1c6-76d9-4659-8349-5441665cca0c","html_texts":"<p>L\\u2019art-th\\u00e9rapie est une discipline qui utilise la cr\\u00e9ation artistique comme moyen d\\u2019expression et de transformation psychique. Elle n\\u2019a pas pour objet la production d\\u2019une \\u0153uvre &quot;belle&quot; ou conforme \\u00e0 des crit\\u00e8res esth\\u00e9tiques, mais met l\\u2019accent sur le <strong>processus<\\/strong> cr\\u00e9atif lui-m\\u00eame : dessiner, modeler, peindre, coller, danser, etc., sont autant de voies pour donner forme \\u00e0 des \\u00e9motions, des pens\\u00e9es, des souffrances ou des d\\u00e9sirs int\\u00e9rioris\\u00e9s.<\\/p><p>Plut\\u00f4t que de parler directement avec des mots, la personne est invit\\u00e9e \\u00e0 utiliser un m\\u00e9dium artistique, qui agit comme un &quot;<a target=\\"_blank\\" rel=\\"noopener noreferrer nofollow\\" href=\\"\\/art-therapie\\">interm\\u00e9diaire<\\/a>&quot; entre le monde int\\u00e9rieur et la conscience. \\u00c0 travers ce support, l\\u2019art-th\\u00e9rapeute accompagne la personne dans la compr\\u00e9hension symbolique de ses cr\\u00e9ations, sans interpr\\u00e9tation autoritaire, dans un cadre s\\u00e9curis\\u00e9 et bienveillant.<\\/p><blockquote><p>En somme, l\\u2019art-th\\u00e9rapie m\\u00eale une posture clinique (\\u00e9coute, bienveillance, diagnostic implicite) et un engagement artistique, permettant l\\u2019acc\\u00e8s \\u00e0 des zones difficiles \\u00e0 verbaliser.<\\/p><\\/blockquote>","html_title":"<p>Qu&#039;est ce que l&#039;<strong>Art-th\\u00e9rapie<\\/strong><\\/p>","left_image":true,"description":null,"photo_config":{"image_url":"image-photos\\/actiing-accompagnement-en-art-therapiejpg-01K7KK1ANPAD40F2SJGRFFMYVY.webp","display_type":"mask_brush_square"},"background_datas":{"mode":"aucun"}},"type":"content"},{"data":{"anchor":null,"ambiance":{"animate":true,"minH70vh":false,"is_hidden":false,"style_listes":"alternance","couleur_primaire":"secondary","afficher_separateur":true},"block_id":"79521918-085c-4ff8-8b58-f81ce1cdfbd0","html_texts":"<p>L\\u2019art-th\\u00e9rapie peut convenir \\u00e0 un public tr\\u00e8s large. Elle ne requiert ni talent artistique ni formation, juste l\\u2019envie d\\u2019explorer ce qu\\u2019on porte int\\u00e9rieurement. On la pratique aupr\\u00e8s de :<\\/p><ul><li><p>enfants, adolescents ou adultes en difficult\\u00e9 \\u00e9motionnelle (anxi\\u00e9t\\u00e9, d\\u00e9pression, stress, burnout)<\\/p><\\/li><li><p>personnes ayant subi un traumatisme, un deuil, une s\\u00e9paration<\\/p><\\/li><li><p>personnes en situation de handicap, de maladie chronique, ou confront\\u00e9es \\u00e0 des troubles neurologiques<\\/p><\\/li><li><p>personnes \\u00e2g\\u00e9es souhaitant entretenir leur lien au monde, stimuler leur cr\\u00e9ativit\\u00e9<\\/p><\\/li><li><p>personnes en qu\\u00eate de d\\u00e9veloppement personnel, de connaissance de soi ou d\\u2019un accompagnement dans une p\\u00e9riode de transition<\\/p><\\/li><\\/ul>","html_title":"<p>Pour <strong>Qui <\\/strong>?<\\/p>","left_image":false,"description":null,"photo_config":{"image_url":"image-photos\\/benefits-of-art-therapy-blog-image-01K7KK32F15Z545ZSDBFKSFEYM.webp","display_type":"mask_brush_square"},"background_datas":{"mode":"aucun"}},"type":"content"},{"data":{"anchor":"portrait","ambiance":{"animate":true,"minH70vh":false,"is_hidden":false,"style_listes":null,"couleur_primaire":"primary","afficher_separateur":false},"block_id":"ac9d6aa5-1246-4e7c-8bd5-b72311297ce1","html_texts":"<p><strong>Nom :<\\/strong> Claire Duval<br><strong>Titre professionnel :<\\/strong> Art-th\\u00e9rapeute dipl\\u00f4m\\u00e9e, m\\u00e9diatrice artistique<br><strong>Parcours et approche :<\\/strong><br>Claire Duval a \\u00e9tudi\\u00e9 les arts plastiques dans une \\u00e9cole d\\u2019art, puis s\\u2019est form\\u00e9e \\u00e0 l\\u2019art-th\\u00e9rapie (formation agr\\u00e9\\u00e9e de trois ans) et \\u00e0 la psychologie de l\\u2019expression. Elle enrichit sa pratique par une sensibilit\\u00e9 aux mat\\u00e9riaux (argile, collage, peinture, mat\\u00e9riaux recycl\\u00e9s).<\\/p><p>Elle travaille en lib\\u00e9ral, dans un atelier baign\\u00e9 de lumi\\u00e8re, tout en intervenant occasionnellement en institutions (maisons de retraite, centres de r\\u00e9\\u00e9ducation). Claire adopte une posture empathique, non jugeante et s\\u00e9curisante : elle propose des consignes souples, invite \\u00e0 l\\u2019exploration libre, et accompagne chaque personne \\u00e0 son rythme.<\\/p><p>Dans sa pratique, elle peut proposer des th\\u00e9matiques (ex. \\u00ab racines \\/ enracinement \\u00bb, \\u00ab m\\u00e9tamorphose \\u00bb) ou laisser une cr\\u00e9ation libre, selon les besoins. Elle accorde une place centrale au dialogue autour de l\\u2019\\u0153uvre produite, pour aider la personne \\u00e0 faire des liens symboliques \\u00e0 sa vie.<\\/p>","html_title":"<p><strong>Portrait<\\/strong> fictif<\\/p>","left_image":false,"description":null,"photo_config":{"image_url":null,"display_type":"mask_brush_square"},"background_datas":{"mode":"aucun"}},"type":"content"}]',
    'statics' => NULL,
    'key_word' => NULL,
    'slug' => 'home',
    'status' => 'published',
    'is_homepage' => true,
    'is_in_header' => false,
    'is_in_footer' => false,
    'has_form' => true,
    'meta_description' => NULL,
    'meta_keywords' => NULL,
    'order' => 1,
    'published_at' => NULL,
    'created_at' => '2025-10-15 09:21:29',
    'updated_at' => '2025-10-15 15:05:51',
  ),
  4 => 
  array (
    'titre' => 'L\'art thérapie',
    'meta_data' => NULL,
    'contents' => '[{"data":{"anchor":"intro","boutons":[{"ancre":"#histoire","texte":"Origines et histoire","couleur":"primary","page_id":"same_page","type_lien":"page"},{"ancre":"#courants","texte":"Courants et approches","couleur":"secondary","page_id":"same_page","type_lien":"page"},{"ancre":"#aujourdhui","texte":"L\\u2019art-th\\u00e9rapie aujourd\\u2019hui","couleur":"primary","page_id":"same_page","type_lien":"page"}],"ambiance":{"animate":true,"minH70vh":true,"is_hidden":false,"couleur_primaire":null},"block_id":"0d62e308-814a-4662-9b67-5ab6bb9c7ba6","html_title":"<p><strong>L&#039;art th\\u00e9rapie<\\/strong><\\/p>","description":"Hier et aujourd\'hui, l\'\\u00e9volution de l\'art th\\u00e9rapie. ","background_datas":{"mask":"hero-mask-2","mode":"filtre","mask_color":"bg-primary-200"}},"type":"hero"},{"data":{"anchor":"histoire","animate":true,"ambiance":{"animate":false,"minH70vh":false,"is_hidden":false,"style_listes":null,"couleur_primaire":null,"afficher_separateur":false},"block_id":"dbb3dd6e-7f53-44df-b2d7-c6ac5158eab6","minH70vh":false,"is_hidden":false,"html_texts":"<p>L\\u2019id\\u00e9e que l\\u2019art pourrait soigner ou soulager les souffrances psychiques ne date pas d\\u2019hier. Dans de nombreuses cultures, la cr\\u00e9ation artistique (peinture, musique, danse, rituels visuels) \\u00e9tait d\\u00e9j\\u00e0 li\\u00e9e \\u00e0 des pratiques de gu\\u00e9rison. Toutefois, l\\u2019art-th\\u00e9rapie en tant que discipline \\u201cofficielle\\u201d est relativement r\\u00e9cente.<\\/p><ul><li><p>Le terme <strong>\\u201cart therapy\\u201d<\\/strong> est souvent attribu\\u00e9 \\u00e0 l\\u2019artiste britannique <strong>Adrian Hill<\\/strong>, qui, au cours de sa convalescence d\\u2019une tuberculose dans les ann\\u00e9es 1940, observa les effets b\\u00e9n\\u00e9fiques de la peinture sur l\\u2019esprit des patients.<\\/p><\\/li><li><p>C\\u2019est apr\\u00e8s la Seconde Guerre mondiale que l\\u2019art th\\u00e9rapeutique s\\u2019est structur\\u00e9 : des artistes et des m\\u00e9decins commenc\\u00e8rent \\u00e0 introduire des ateliers artistiques dans des h\\u00f4pitaux psychiatriques ou des \\u00e9tablissements de soins pour personnes traumatis\\u00e9es.<\\/p><\\/li><li><p>Parmi les pionniers, <strong>Edward Adamson<\\/strong> (Royaume-Uni) est un nom souvent cit\\u00e9. Il mit en place, d\\u00e8s les ann\\u00e9es 1940\\u201350 dans des h\\u00f4pitaux psychiatriques, des ateliers d\\u2019art libre o\\u00f9 les patients pouvaient cr\\u00e9er sans contrainte directe, sans interpr\\u00e9tation impos\\u00e9e, dans un cadre bienveillant.<\\/p><\\/li><li><p>En France, les \\u00e9crits de <strong>Walter Morgenthaler<\\/strong> (notamment autour du cas d\\u2019Adolf W\\u00f6lfli) au d\\u00e9but du XX\\u1d49 si\\u00e8cle sont souvent cit\\u00e9s comme ant\\u00e9c\\u00e9dents importants reliant maladie mentale et cr\\u00e9ation spontane\\u0301e.<\\/p><\\/li><li><p>Au XX\\u1d49 si\\u00e8cle, plusieurs figures m\\u00e9diatrices, \\u00e9ducateurs et psychiatres (dont Margaret Naumburg, Edith Kramer, Jean-Pierre Klein, Guy Lafargue) ont contribu\\u00e9 \\u00e0 formaliser des approches, des cadres cliniques et des formations.<\\/p><\\/li><\\/ul><p>Jean-Pierre Klein, en particulier, est une figure marquante en France pour la th\\u00e9orisation de l\\u2019art-th\\u00e9rapie, la m\\u00e9diation artistique et la relation d\\u2019aide.<\\/p><p>Un autre lieu embl\\u00e9matique est la <strong>Haus der K\\u00fcnstler<\\/strong> (Autriche), proche de Vienne, qui accueille des personnes souffrant de troubles psychiatriques et les associe \\u00e0 la cr\\u00e9ation artistique, en respectant leur statut d\\u2019artistes.<\\/p>","html_title":"<p>L\\u2019art-th\\u00e9rapie : une discipline en <strong>mutation<\\/strong><\\/p>","left_image":false,"description":null,"photo_config":{"image_url":null,"display_type":"mask_brush_square"},"style_listes":"alternance","background_datas":{"mode":"aucun"},"couleur_primaire":"secondary","afficher_separateur":false},"type":"content"},{"data":{"anchor":"courants","animate":true,"ambiance":{"animate":false,"minH70vh":false,"is_hidden":false,"style_listes":null,"couleur_primaire":null,"afficher_separateur":false},"block_id":"8111609b-5a65-4d0c-af78-8c18b3bbd3e4","minH70vh":false,"is_hidden":false,"html_texts":"<ol start=\\"1\\"><li><p><strong>L\\u2019approche analytique \\/ psychanalytique<\\/strong><br>Inspir\\u00e9e de la psychanalyse et de la psychologie analytique, cette approche voit dans les images, les formes et les symboles des expressions de l\\u2019inconscient ou du monde int\\u00e9rieur. Le praticien peut explorer les significations psychiques des \\u0153uvres, dans un dialogue avec le cr\\u00e9ateur.<\\/p><\\/li><li><p><strong>L\\u2019approche centr\\u00e9e sur le processus (expression libre, m\\u00e9diation libre)<\\/strong><br>Ici, ce n\\u2019est pas le produit qui compte, mais l\\u2019acte cr\\u00e9atif lui-m\\u00eame. Le r\\u00f4le de l\\u2019art-th\\u00e9rapeute est d\\u2019offrir un espace s\\u00e9curisant, d\\u2019accompagner sans imposer d\\u2019interpr\\u00e9tation trop directe, mais de laisser \\u00e9merger ce que la personne exprime spontan\\u00e9ment. Beaucoup de praticiens et d\\u2019ateliers privil\\u00e9gient ce courant pour sa dimension respectueuse des rythmes individuels.<\\/p><\\/li><li><p><strong>L\\u2019approche intermodale \\/ expressive arts therapy<\\/strong><br>Ce courant, souvent appel\\u00e9 \\u201cExpressive Arts Therapy\\u201d ou \\u201cArts d\\u2019expression\\u201d, int\\u00e8gre plusieurs modes artistiques \\u2014 peinture, musique, danse, th\\u00e9\\u00e2tre, \\u00e9criture \\u2014 dans une m\\u00eame s\\u00e9ance ou un m\\u00eame processus, en permettant \\u00e0 la personne de passer d\\u2019une forme \\u00e0 l\\u2019autre selon ses besoins.<\\/p><\\/li><li><p><strong>L\\u2019art brut \\/ outsider art comme source inspiratrice<\\/strong><br>L\\u2019int\\u00e9r\\u00eat pour les \\u0153uvres spontan\\u00e9es produites par des personnes hors du monde artistique \\u201ccanonique\\u201d (patients psychiatriques, cr\\u00e9ateurs autodidactes, marginaux) a nourri beaucoup de r\\u00e9flexions autour de la valeur expressive, non norm\\u00e9e, de la cr\\u00e9ation. Jean Dubuffet, \\u00e0 travers le mouvement de l\\u2019<strong>art brut<\\/strong>, s\\u2019est int\\u00e9ress\\u00e9 \\u00e0 ces cr\\u00e9ations \\u201chors normes\\u201d.<\\/p><\\/li><li><p><strong>Approches institutionnelles, m\\u00e9diations en institution, art en h\\u00f4pital<\\/strong><br>Certains courants privil\\u00e9gient l\\u2019int\\u00e9gration de l\\u2019art dans le champ m\\u00e9dico-social, hospitalier ou institutionnel, pour offrir des ateliers collectifs, des cycles de m\\u00e9diation artistique, des expositions participatives, ou des interventions en milieu hospitalier. La m\\u00e9diation artistique y joue un r\\u00f4le de lien entre le soin, la cr\\u00e9ation et la vie sociale.<\\/p><\\/li><\\/ol>","html_title":"<p>Les grands <strong>courants et approches<\\/strong> de l\\u2019art-th\\u00e9rapie<\\/p>","left_image":false,"description":"Il n\\u2019y a pas \\u201cune\\u201d seule fa\\u00e7on de pratiquer l\\u2019art-th\\u00e9rapie : plusieurs approches coexistent, selon les r\\u00e9f\\u00e9rences th\\u00e9oriques, les choix m\\u00e9thodologiques, le r\\u00f4le donn\\u00e9 au m\\u00e9dium artistique et le positionnement du praticien. Voici quelques grands courants :","photo_config":{"image_url":null,"display_type":"mask_brush_square"},"style_listes":"alternance","background_datas":{"mode":"aucun"},"couleur_primaire":"secondary","afficher_separateur":false},"type":"content"}]',
    'statics' => NULL,
    'key_word' => NULL,
    'slug' => 'art-therapie',
    'status' => 'published',
    'is_homepage' => false,
    'is_in_header' => true,
    'is_in_footer' => false,
    'has_form' => false,
    'meta_description' => NULL,
    'meta_keywords' => NULL,
    'order' => 4,
    'published_at' => NULL,
    'created_at' => '2025-10-15 09:50:22',
    'updated_at' => '2025-10-15 14:12:10',
  ),
);
        
        foreach ($pages as $page) {
            DB::table('cms_pages')->insert($page);
        }
    }
}