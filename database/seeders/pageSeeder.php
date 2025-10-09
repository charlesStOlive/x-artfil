<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class pageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Generated on 2025-10-09 09:39:34
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
        $seederImagesPath = database_path('seeders/images/page_seeder');
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
  'jay-lamm-al-feka1g1g-unsplash-e1666975302144-01k6we2tdrzv1vd8q6rz05xa7j-v1-01K6WK8J603S36NB1SS640SY1G.webp' => 'images-bg/jay-lamm-al-feka1g1g-unsplash-e1666975302144-01k6we2tdrzv1vd8q6rz05xa7j-v1-01K6WK8J603S36NB1SS640SY1G.webp',
  '66365894-47155168-01K7429HETCE8590K04STSGQ53.webp' => 'images-bg/66365894-47155168-01K7429HETCE8590K04STSGQ53.webp',
  'adamsonportrait-01K6ZGSX8172PQAZR3XMBWSQ0K.webp' => 'image-photos/adamsonportrait-01K6ZGSX8172PQAZR3XMBWSQ0K.webp',
  'benefits-of-art-therapy-blog-image-01K6ZGTX7ZB05N71GATTJQRMQC.webp' => 'image-photos/benefits-of-art-therapy-blog-image-01K6ZGTX7ZB05N71GATTJQRMQC.webp',
  'csm-saint-olive-pauline-mdl-jrambaud-1920-aee5bec745-01K74160AG3Q1N24Y8DCRT0B00.webp' => 'image-photos/csm-saint-olive-pauline-mdl-jrambaud-1920-aee5bec745-01K74160AG3Q1N24Y8DCRT0B00.webp',
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
    'titre' => 'Accueil',
    'meta_data' => NULL,
    'contents' => '[{"data":{"title":"<p>L\\u2019art-th\\u00e9rapie<\\/p><p><strong>Cr\\u00e9er <\\/strong>pour <strong>gu\\u00e9rir<\\/strong><\\/p>","anchor":null,"animate":true,"boutons":[{"ancre":"#decouvrir","texte":"D\\u00e9couvrir l\'art-th\\u00e9rapie","couleur":"primary","page_id":null,"type_lien":"page"},{"ancre":"#contact","texte":"Prendre rendez-vous","couleur":"secondary","page_id":null,"type_lien":"page"}],"block_id":"46154c64-7cc9-4164-b88c-b14a341fd103","is_hidden":false,"description":"L\\u2019art-th\\u00e9rapie est une d\\u00e9marche d\\u2019accompagnement par la cr\\u00e9ation artistique, visant \\u00e0 explorer ses \\u00e9motions, soulager des souffrances psychiques ou favoriser un mieux-\\u00eatre global. Elle s\\u2019adresse \\u00e0 tous, sans besoin de comp\\u00e9tences artistiques, et repose sur la relation entre le\\u2019individu, le mat\\u00e9riau et le praticien.","couche_blanc":"normal","style_listes":"primary","background_image":"images-bg\\/jay-lamm-al-feka1g1g-unsplash-e1666975302144-01k6we2tdrzv1vd8q6rz05xa7j-v1-01K6WK8J603S36NB1SS640SY1G.webp","couleur_primaire":"primary","direction_couleur":"primaire-secondaire","afficher_separateur":true},"type":"hero"},{"data":{"texts":"<p style=\\"text-align: center;\\">En vacance jusqu&#039;au <strong>XXXX<\\/strong><\\/p>","title":"<p>Le cabinet est <strong>ferm\\u00e9<\\/strong><\\/p>","anchor":null,"animate":true,"block_id":"bfffb69d-073f-4bf3-9f3f-e52224e00fa4","is_hidden":true,"description":null,"image_right":false,"couche_blanc":"normal","photo_config":{"url":null,"display_type":"mask_brush_square"},"style_listes":"alternance","background_image":"images-bg\\/66365894-47155168-01K7429HETCE8590K04STSGQ53.webp","couleur_primaire":"secondary","direction_couleur":"secondaire-primaire","afficher_separateur":true},"type":"content"},{"data":{"texts":"<p>L\\u2019art-th\\u00e9rapie est une discipline qui utilise la cr\\u00e9ation artistique comme moyen d\\u2019expression et de transformation psychique. Elle n\\u2019a pas pour objet la production d\\u2019une \\u0153uvre &quot;belle&quot; ou conforme \\u00e0 des crit\\u00e8res esth\\u00e9tiques, mais met l\\u2019accent sur le <strong>processus<\\/strong> cr\\u00e9atif lui-m\\u00eame : dessiner, modeler, peindre, coller, danser, etc., sont autant de voies pour donner forme \\u00e0 des \\u00e9motions, des pens\\u00e9es, des souffrances ou des d\\u00e9sirs int\\u00e9rioris\\u00e9s.<\\/p><p><a target=\\"_blank\\" rel=\\"noopener noreferrer nofollow\\" href=\\"\\/#portrait\\">Plut\\u00f4t <\\/a>que de parler directement avec des mots, la <a target=\\"_blank\\" rel=\\"noopener noreferrer nofollow\\" href=\\"\\/#portrait\\">personne<\\/a> est invit\\u00e9e \\u00e0 utiliser un m\\u00e9dium artistique, qui agit comme un &quot;<a target=\\"_blank\\" rel=\\"noopener noreferrer nofollow\\" href=\\"\\/#portrait\\">interm\\u00e9diaire<\\/a>&quot; entre le monde int\\u00e9rieur et la conscience. \\u00c0 travers ce support, l\\u2019art-th\\u00e9rapeute accompagne la personne dans la compr\\u00e9hension symbolique de ses cr\\u00e9ations, sans interpr\\u00e9tation autoritaire, dans un cadre s\\u00e9curis\\u00e9 et bienveillant.<\\/p><blockquote><p>En somme, l\\u2019art-th\\u00e9rapie m\\u00eale une posture clinique (\\u00e9coute, bienveillance, diagnostic implicite) et un engagement artistique, permettant l\\u2019acc\\u00e8s \\u00e0 des zones difficiles \\u00e0 verbaliser.<\\/p><\\/blockquote>","title":"<p>Qu&#039;est ce que l&#039;<strong>Art-th\\u00e9rapie<\\/strong><\\/p>","anchor":"decouvrir","animate":false,"block_id":"4ebd6d53-1b09-42c9-b15c-217561c07851","is_hidden":false,"description":null,"image_right":false,"couche_blanc":null,"photo_config":{"url":"image-photos\\/adamsonportrait-01K6ZGSX8172PQAZR3XMBWSQ0K.webp","display_type":"mask_brush_square"},"style_listes":null,"background_image":null,"couleur_primaire":"secondary","direction_couleur":null,"afficher_separateur":true},"type":"content"},{"data":{"texts":"<p>L\\u2019art-th\\u00e9rapie peut convenir \\u00e0 un public tr\\u00e8s large. Elle ne requiert ni talent artistique ni formation, juste l\\u2019envie d\\u2019explorer ce qu\\u2019on porte int\\u00e9rieurement. On la pratique aupr\\u00e8s de :<\\/p><ul><li><p>enfants, adolescents ou adultes en difficult\\u00e9 \\u00e9motionnelle (anxi\\u00e9t\\u00e9, d\\u00e9pression, stress, burnout)<\\/p><\\/li><li><p>personnes ayant subi un traumatisme, un deuil, une s\\u00e9paration<\\/p><\\/li><li><p>personnes en situation de handicap, de maladie chronique, ou confront\\u00e9es \\u00e0 des troubles neurologiques<\\/p><\\/li><li><p>personnes \\u00e2g\\u00e9es souhaitant entretenir leur lien au monde, stimuler leur cr\\u00e9ativit\\u00e9<\\/p><\\/li><li><p>personnes en qu\\u00eate de d\\u00e9veloppement personnel, de connaissance de soi ou d\\u2019un accompagnement dans une p\\u00e9riode de transition<\\/p><\\/li><\\/ul>","title":"<p>Pour <strong>Qui <\\/strong>?<\\/p>","anchor":null,"animate":false,"block_id":"fd98c0ae-8867-4630-bdbe-43b91137cdfa","is_hidden":false,"description":null,"image_right":true,"couche_blanc":null,"photo_config":{"url":"image-photos\\/benefits-of-art-therapy-blog-image-01K6ZGTX7ZB05N71GATTJQRMQC.webp","display_type":"mask_brush_square"},"style_listes":"primary","background_image":null,"couleur_primaire":"primary","direction_couleur":"primaire-secondaire","afficher_separateur":true},"type":"content"},{"data":{"texts":"<p><strong>Nom :<\\/strong> Claire Duval<br><strong>Titre professionnel :<\\/strong> Art-th\\u00e9rapeute dipl\\u00f4m\\u00e9e, m\\u00e9diatrice artistique<br><strong>Parcours et approche :<\\/strong><br>Claire Duval a \\u00e9tudi\\u00e9 les arts plastiques dans une \\u00e9cole d\\u2019art, puis s\\u2019est form\\u00e9e \\u00e0 l\\u2019art-th\\u00e9rapie (formation agr\\u00e9\\u00e9e de trois ans) et \\u00e0 la psychologie de l\\u2019expression. Elle enrichit sa pratique par une sensibilit\\u00e9 aux mat\\u00e9riaux (argile, collage, peinture, mat\\u00e9riaux recycl\\u00e9s).<\\/p><p>Elle travaille en lib\\u00e9ral, dans un atelier baign\\u00e9 de lumi\\u00e8re, tout en intervenant occasionnellement en institutions (maisons de retraite, centres de r\\u00e9\\u00e9ducation). Claire adopte une posture empathique, non jugeante et s\\u00e9curisante : elle propose des consignes souples, invite \\u00e0 l\\u2019exploration libre, et accompagne chaque personne \\u00e0 son rythme.<\\/p><p>Dans sa pratique, elle peut proposer des th\\u00e9matiques (ex. \\u00ab racines \\/ enracinement \\u00bb, \\u00ab m\\u00e9tamorphose \\u00bb) ou laisser une cr\\u00e9ation libre, selon les besoins. Elle accorde une place centrale au dialogue autour de l\\u2019\\u0153uvre produite, pour aider la personne \\u00e0 faire des liens symboliques \\u00e0 sa vie.<\\/p>","title":"<p><strong>Portrait<\\/strong> fictif<\\/p>","anchor":"portrait","animate":false,"block_id":"15fc1575-9eed-49fa-97d1-db4646a4c7ac","is_hidden":false,"description":null,"image_right":false,"couche_blanc":"aucun","photo_config":{"url":"image-photos\\/csm-saint-olive-pauline-mdl-jrambaud-1920-aee5bec745-01K74160AG3Q1N24Y8DCRT0B00.webp","display_type":null},"style_listes":"alternance","background_image":null,"couleur_primaire":"secondary","direction_couleur":"aucun","afficher_separateur":false},"type":"content"}]',
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
    'created_at' => '2025-10-01 08:55:00',
    'updated_at' => '2025-10-09 08:47:53',
  ),
  1 => 
  array (
    'titre' => 'Mentions légales',
    'meta_data' => NULL,
    'contents' => '[{"data":{"texts":"<h1>Mentions l\\u00e9gales<\\/h1><h2>\\u00c9diteur du site<\\/h2><p>Le pr\\u00e9sent site est \\u00e9dit\\u00e9 par :<br><strong>Notilac<\\/strong><br>Repr\\u00e9sentant l\\u00e9gal : <strong>Charles Saint Olive<\\/strong><br>Adresse : 69160 Tassin, France<br>E-mail : <a target=\\"_blank\\" rel=\\"noopener noreferrer nofollow\\" href=\\"mailto:charles@notilac.fr\\">charles@notilac.fr<\\/a><\\/p><h2>H\\u00e9bergeur<\\/h2><p>L\\u2019h\\u00e9bergement du site est assur\\u00e9 par :<br><strong>DigitalOcean, LLC<\\/strong><br>Si\\u00e8ge social : 101 Avenue of the Americas, 10th Floor, New York, NY 10013, USA<br>Localisation du serveur : <strong>Allemagne<\\/strong><br>Site web : <a target=\\"_blank\\" rel=\\"noopener noreferrer nofollow\\" href=\\"https:\\/\\/www.digitalocean.com\\">https:\\/\\/www.digitalocean.com<\\/a><\\/p><h2>Propri\\u00e9t\\u00e9 intellectuelle<\\/h2><p>L\\u2019ensemble des contenus pr\\u00e9sents sur ce site (textes, images, graphismes, logos, vid\\u00e9os, etc.) est prot\\u00e9g\\u00e9 par le droit de la propri\\u00e9t\\u00e9 intellectuelle. Toute reproduction, repr\\u00e9sentation, modification ou exploitation, totale ou partielle, sans l\\u2019autorisation pr\\u00e9alable de l\\u2019\\u00e9diteur est interdite.<\\/p><h2>Donn\\u00e9es personnelles<\\/h2><p>Ce site ne collecte pas de cookies tiers.<br>Les informations \\u00e9ventuellement transmises via le formulaire de contact (si existant) sont utilis\\u00e9es uniquement pour r\\u00e9pondre aux demandes des utilisateurs. Elles ne sont ni revendues ni transmises \\u00e0 des tiers.<\\/p><p>Conform\\u00e9ment au R\\u00e8glement G\\u00e9n\\u00e9ral sur la Protection des Donn\\u00e9es (RGPD \\u2013 UE 2016\\/679) et \\u00e0 la loi Informatique et Libert\\u00e9s, vous disposez d\\u2019un droit d\\u2019acc\\u00e8s, de rectification et de suppression de vos donn\\u00e9es.<br>Vous pouvez exercer ces droits en \\u00e9crivant \\u00e0 : <a target=\\"_blank\\" rel=\\"noopener noreferrer nofollow\\" href=\\"mailto:charles@notilac.fr\\">charles@notilac.fr<\\/a>.<\\/p><h2>Responsabilit\\u00e9<\\/h2><p>L\\u2019\\u00e9diteur s\\u2019efforce d\\u2019assurer l\\u2019exactitude des informations diffus\\u00e9es sur ce site. Toutefois, il ne saurait \\u00eatre tenu responsable des erreurs ou omissions, ni d\\u2019un \\u00e9ventuel dysfonctionnement ou interruption de disponibilit\\u00e9 du site.<\\/p><p>L\\u2019utilisation des liens hypertextes peut conduire l\\u2019utilisateur vers d\\u2019autres sites sur lesquels l\\u2019\\u00e9diteur n\\u2019exerce aucun contr\\u00f4le et d\\u00e9cline toute responsabilit\\u00e9.<\\/p>","title":"<p><\\/p>","anchor":null,"block_id":"c844644b-e08f-441f-9616-68aba28bb7b5","description":null,"image_right":false,"couche_blanc":"aucun","style_listes":"alternance","secondary_text":false,"background_image":null,"couleur_primaire":"secondary","direction_couleur":"aucun","afficher_separateur":false,"section_background_image":null},"type":"content"}]',
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
    'order' => 4,
    'published_at' => '2025-10-03 10:27:33',
    'created_at' => '2025-10-03 08:31:08',
    'updated_at' => '2025-10-04 15:46:39',
  ),
  2 => 
  array (
    'titre' => 'Politique de confidentialité',
    'meta_data' => NULL,
    'contents' => '[{"data":{"texts":"<h1>Politique de confidentialit\\u00e9<\\/h1><h2>1. Responsable du traitement<\\/h2><p>Le responsable du traitement des donn\\u00e9es personnelles collect\\u00e9es sur ce site est :<br><strong>Notilac \\u2013 Charles Saint Olive<\\/strong><br>69160 Tassin, France<br>E-mail : <a target=\\"_blank\\" rel=\\"noopener noreferrer nofollow\\" href=\\"mailto:charles@notilac.fr\\">charles@notilac.fr<\\/a><\\/p><h2>2. Donn\\u00e9es collect\\u00e9es<\\/h2><p>Ce site n\\u2019utilise <strong>aucun cookie tiers<\\/strong> \\u00e0 des fins publicitaires ou de suivi.<br>Les seules donn\\u00e9es personnelles susceptibles d\\u2019\\u00eatre collect\\u00e9es sont celles que vous transmettez volontairement, par exemple via :<\\/p><ul><li><p>un formulaire de contact,<\\/p><\\/li><li><p>l\\u2019envoi d\\u2019un e-mail direct.<\\/p><\\/li><\\/ul><h2>3. Finalit\\u00e9s du traitement<\\/h2><p>Les donn\\u00e9es transmises sont utilis\\u00e9es uniquement pour :<\\/p><ul><li><p>r\\u00e9pondre \\u00e0 vos demandes,<\\/p><\\/li><li><p>assurer le suivi des \\u00e9changes,<\\/p><\\/li><li><p>am\\u00e9liorer la qualit\\u00e9 des services propos\\u00e9s.<\\/p><\\/li><\\/ul><p>Aucune donn\\u00e9e n\\u2019est utilis\\u00e9e \\u00e0 des fins commerciales ou transmises \\u00e0 des tiers sans votre consentement.<\\/p><h2>4. Base l\\u00e9gale du traitement<\\/h2><p>Le traitement de vos donn\\u00e9es repose sur :<\\/p><ul><li><p><strong>votre consentement<\\/strong> lorsque vous nous transmettez volontairement vos informations,<\\/p><\\/li><li><p><strong>l\\u2019int\\u00e9r\\u00eat l\\u00e9gitime<\\/strong> de l\\u2019\\u00e9diteur pour r\\u00e9pondre \\u00e0 vos demandes.<\\/p><\\/li><\\/ul><h2>5. Dur\\u00e9e de conservation<\\/h2><p>Les donn\\u00e9es collect\\u00e9es sont conserv\\u00e9es uniquement le temps n\\u00e9cessaire pour traiter votre demande. Elles peuvent \\u00eatre supprim\\u00e9es \\u00e0 tout moment sur simple demande.<\\/p><h2>6. Partage des donn\\u00e9es<\\/h2><p>Vos donn\\u00e9es ne sont ni vendues, ni lou\\u00e9es, ni partag\\u00e9es \\u00e0 des tiers. Elles peuvent \\u00eatre transmises uniquement si la loi l\\u2019exige (obligations l\\u00e9gales, judiciaires ou r\\u00e9glementaires).<\\/p><h2>7. S\\u00e9curit\\u00e9<\\/h2><p>Nous mettons en \\u0153uvre toutes les mesures techniques et organisationnelles n\\u00e9cessaires pour prot\\u00e9ger vos donn\\u00e9es personnelles contre l\\u2019acc\\u00e8s non autoris\\u00e9, la perte, la destruction ou la divulgation.<\\/p><h2>8. Vos droits<\\/h2><p>Conform\\u00e9ment au R\\u00e8glement G\\u00e9n\\u00e9ral sur la Protection des Donn\\u00e9es (RGPD \\u2013 UE 2016\\/679) et \\u00e0 la loi Informatique et Libert\\u00e9s, vous disposez des droits suivants :<\\/p><ul><li><p>droit d\\u2019acc\\u00e8s,<\\/p><\\/li><li><p>droit de rectification,<\\/p><\\/li><li><p>droit \\u00e0 l\\u2019effacement,<\\/p><\\/li><li><p>droit \\u00e0 la limitation du traitement,<\\/p><\\/li><li><p>droit d\\u2019opposition,<\\/p><\\/li><li><p>droit \\u00e0 la portabilit\\u00e9 de vos donn\\u00e9es.<\\/p><\\/li><\\/ul><p>Pour exercer ces droits, vous pouvez contacter : <a target=\\"_blank\\" rel=\\"noopener noreferrer nofollow\\" href=\\"mailto:charles@notilac.fr\\">charles@notilac.fr<\\/a>.<\\/p><h2>9. Contact<\\/h2><p>Pour toute question concernant la pr\\u00e9sente politique de confidentialit\\u00e9 ou le traitement de vos donn\\u00e9es personnelles, vous pouvez nous \\u00e9crire \\u00e0 l\\u2019adresse e-mail suivante : <a target=\\"_blank\\" rel=\\"noopener noreferrer nofollow\\" href=\\"mailto:charles@notilac.fr\\">charles@notilac.fr<\\/a>.<\\/p>","title":"<p><\\/p>","anchor":null,"block_id":"f5c01d5d-c6cc-4df5-ab09-9a8f60f9bed0","description":null,"image_right":false,"couche_blanc":"aucun","style_listes":"primary","secondary_text":false,"background_image":null,"couleur_primaire":"primary","direction_couleur":"aucun","afficher_separateur":false,"section_background_image":null},"type":"content"}]',
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
    'order' => 5,
    'published_at' => NULL,
    'created_at' => '2025-10-03 08:49:19',
    'updated_at' => '2025-10-04 15:46:39',
  ),
  3 => 
  array (
    'titre' => 'Mon profil',
    'meta_data' => NULL,
    'contents' => '[{"data":{"photo":"static_pages_photos\\/csm-saint-olive-pauline-mdl-jrambaud-1920-aee5bec745-01K6W868K00N2D36F16QE9F3QF.webp","texts":"<p><strong>Nom :<\\/strong> Claire Duval<br><strong>Titre professionnel :<\\/strong> Art-th\\u00e9rapeute dipl\\u00f4m\\u00e9e, m\\u00e9diatrice artistique<br><strong>Parcours et approche :<\\/strong><br>Claire Duval a \\u00e9tudi\\u00e9 les arts plastiques dans une \\u00e9cole d\\u2019art, puis s\\u2019est form\\u00e9e \\u00e0 l\\u2019art-th\\u00e9rapie (formation agr\\u00e9\\u00e9e de trois ans) et \\u00e0 la psychologie de l\\u2019expression. Elle enrichit sa pratique par une sensibilit\\u00e9 aux mat\\u00e9riaux (argile, collage, peinture, mat\\u00e9riaux recycl\\u00e9s).<\\/p><p>Elle travaille en lib\\u00e9ral, dans un atelier baign\\u00e9 de lumi\\u00e8re, tout en intervenant occasionnellement en institutions (maisons de retraite, centres de r\\u00e9\\u00e9ducation). Claire adopte une posture empathique, non jugeante et s\\u00e9curisante : elle propose des consignes souples, invite \\u00e0 l\\u2019exploration libre, et accompagne chaque personne \\u00e0 son rythme.<\\/p><p>Dans sa pratique, elle peut proposer des th\\u00e9matiques (ex. \\u00ab racines \\/ enracinement \\u00bb, \\u00ab m\\u00e9tamorphose \\u00bb) ou laisser une cr\\u00e9ation libre, selon les besoins. Elle accorde une place centrale au dialogue autour de l\\u2019\\u0153uvre produite, pour aider la personne \\u00e0 faire des liens symboliques \\u00e0 sa vie.<\\/p>","title":"<h2>Portrait fictif d\\u2019un praticien<\\/h2>","anchor":null,"animate":false,"block_id":"0135f0ea-52d6-4a43-9147-7ca0624c7aff","is_hidden":false,"description":null,"image_right":false,"couche_blanc":null,"photo_config":{"url":null,"display_type":null},"style_listes":"alternance","secondary_text":false,"section_styles":{"couche_blanc":null,"background_image":null,"direction_couleur":null},"background_image":null,"couleur_primaire":"secondary","direction_couleur":null,"photo_display_type":"mask_brush_square","afficher_separateur":false},"type":"content"}]',
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
    'order' => 2,
    'published_at' => '2025-10-03 10:51:11',
    'created_at' => '2025-10-03 08:51:18',
    'updated_at' => '2025-10-08 16:00:39',
  ),
  4 => 
  array (
    'titre' => 'L\'art thérapie',
    'meta_data' => NULL,
    'contents' => '[{"data":{"title":"<p><strong>L&#039;art th\\u00e9rapie<\\/strong><\\/p>","anchor":"intro","animate":false,"boutons":[{"ancre":"#histoire","texte":"Origines et histoire","couleur":"primary","page_id":null,"type_lien":"page"},{"ancre":"#courants","texte":"Courants et approches","couleur":"secondary","page_id":null,"type_lien":"page"},{"ancre":"#aujourdhui","texte":"L\\u2019art-th\\u00e9rapie aujourd\\u2019hui","couleur":"primary","page_id":null,"type_lien":"page"}],"block_id":"d277de88-9b58-41c9-9a7a-a5934f508a54","is_hidden":false,"description":"Hier et aujourd\'hui, l\'\\u00e9volution de l\'art th\\u00e9rapie. ","couche_blanc":"aucun","style_listes":null,"background_image":null,"couleur_primaire":null,"direction_couleur":"primaire-secondaire","afficher_separateur":false},"type":"hero"},{"data":{"photo":null,"texts":"<p>L\\u2019id\\u00e9e que l\\u2019art pourrait soigner ou soulager les souffrances psychiques ne date pas d\\u2019hier. Dans de nombreuses cultures, la cr\\u00e9ation artistique (peinture, musique, danse, rituels visuels) \\u00e9tait d\\u00e9j\\u00e0 li\\u00e9e \\u00e0 des pratiques de gu\\u00e9rison. Toutefois, l\\u2019art-th\\u00e9rapie en tant que discipline \\u201cofficielle\\u201d est relativement r\\u00e9cente.<\\/p><ul><li><p>Le terme <strong>\\u201cart therapy\\u201d<\\/strong> est souvent attribu\\u00e9 \\u00e0 l\\u2019artiste britannique <strong>Adrian Hill<\\/strong>, qui, au cours de sa convalescence d\\u2019une tuberculose dans les ann\\u00e9es 1940, observa les effets b\\u00e9n\\u00e9fiques de la peinture sur l\\u2019esprit des patients.<\\/p><\\/li><li><p>C\\u2019est apr\\u00e8s la Seconde Guerre mondiale que l\\u2019art th\\u00e9rapeutique s\\u2019est structur\\u00e9 : des artistes et des m\\u00e9decins commenc\\u00e8rent \\u00e0 introduire des ateliers artistiques dans des h\\u00f4pitaux psychiatriques ou des \\u00e9tablissements de soins pour personnes traumatis\\u00e9es.<\\/p><\\/li><li><p>Parmi les pionniers, <strong>Edward Adamson<\\/strong> (Royaume-Uni) est un nom souvent cit\\u00e9. Il mit en place, d\\u00e8s les ann\\u00e9es 1940\\u201350 dans des h\\u00f4pitaux psychiatriques, des ateliers d\\u2019art libre o\\u00f9 les patients pouvaient cr\\u00e9er sans contrainte directe, sans interpr\\u00e9tation impos\\u00e9e, dans un cadre bienveillant.<\\/p><\\/li><li><p>En France, les \\u00e9crits de <strong>Walter Morgenthaler<\\/strong> (notamment autour du cas d\\u2019Adolf W\\u00f6lfli) au d\\u00e9but du XX\\u1d49 si\\u00e8cle sont souvent cit\\u00e9s comme ant\\u00e9c\\u00e9dents importants reliant maladie mentale et cr\\u00e9ation spontane\\u0301e.<\\/p><\\/li><li><p>Au XX\\u1d49 si\\u00e8cle, plusieurs figures m\\u00e9diatrices, \\u00e9ducateurs et psychiatres (dont Margaret Naumburg, Edith Kramer, Jean-Pierre Klein, Guy Lafargue) ont contribu\\u00e9 \\u00e0 formaliser des approches, des cadres cliniques et des formations.<\\/p><\\/li><\\/ul><p>Jean-Pierre Klein, en particulier, est une figure marquante en France pour la th\\u00e9orisation de l\\u2019art-th\\u00e9rapie, la m\\u00e9diation artistique et la relation d\\u2019aide.<\\/p><p>Un autre lieu embl\\u00e9matique est la <strong>Haus der K\\u00fcnstler<\\/strong> (Autriche), proche de Vienne, qui accueille des personnes souffrant de troubles psychiatriques et les associe \\u00e0 la cr\\u00e9ation artistique, en respectant leur statut d\\u2019artistes.<\\/p>","title":"<p>L\\u2019art-th\\u00e9rapie : une discipline en mutation<\\/p>","anchor":"histoire","animate":false,"block_id":"a24cd7c3-17e8-4ccc-8587-df508ca0a8e9","is_hidden":false,"description":"origines, courants et actualit\\u00e9","image_right":true,"couche_blanc":null,"photo_config":{"url":null,"display_type":null},"style_listes":"alternance","secondary_text":false,"section_styles":{"couche_blanc":"fort","background_image":"hero-images\\/benefits-of-art-therapy-blog-image-01K6QYNDWKK470TNFJD5W4C5Z3.webp","direction_couleur":"primaire-secondaire"},"background_image":null,"couleur_primaire":"secondary","direction_couleur":null,"photo_display_type":null,"afficher_separateur":false},"type":"content"},{"data":{"photo":null,"texts":"<ol start=\\"1\\"><li><p><strong>L\\u2019approche analytique \\/ psychanalytique<\\/strong><br>Inspir\\u00e9e de la psychanalyse et de la psychologie analytique, cette approche voit dans les images, les formes et les symboles des expressions de l\\u2019inconscient ou du monde int\\u00e9rieur. Le praticien peut explorer les significations psychiques des \\u0153uvres, dans un dialogue avec le cr\\u00e9ateur.<\\/p><\\/li><li><p><strong>L\\u2019approche centr\\u00e9e sur le processus (expression libre, m\\u00e9diation libre)<\\/strong><br>Ici, ce n\\u2019est pas le produit qui compte, mais l\\u2019acte cr\\u00e9atif lui-m\\u00eame. Le r\\u00f4le de l\\u2019art-th\\u00e9rapeute est d\\u2019offrir un espace s\\u00e9curisant, d\\u2019accompagner sans imposer d\\u2019interpr\\u00e9tation trop directe, mais de laisser \\u00e9merger ce que la personne exprime spontan\\u00e9ment. Beaucoup de praticiens et d\\u2019ateliers privil\\u00e9gient ce courant pour sa dimension respectueuse des rythmes individuels.<\\/p><\\/li><li><p><strong>L\\u2019approche intermodale \\/ expressive arts therapy<\\/strong><br>Ce courant, souvent appel\\u00e9 \\u201cExpressive Arts Therapy\\u201d ou \\u201cArts d\\u2019expression\\u201d, int\\u00e8gre plusieurs modes artistiques \\u2014 peinture, musique, danse, th\\u00e9\\u00e2tre, \\u00e9criture \\u2014 dans une m\\u00eame s\\u00e9ance ou un m\\u00eame processus, en permettant \\u00e0 la personne de passer d\\u2019une forme \\u00e0 l\\u2019autre selon ses besoins.<\\/p><\\/li><li><p><strong>L\\u2019art brut \\/ outsider art comme source inspiratrice<\\/strong><br>L\\u2019int\\u00e9r\\u00eat pour les \\u0153uvres spontan\\u00e9es produites par des personnes hors du monde artistique \\u201ccanonique\\u201d (patients psychiatriques, cr\\u00e9ateurs autodidactes, marginaux) a nourri beaucoup de r\\u00e9flexions autour de la valeur expressive, non norm\\u00e9e, de la cr\\u00e9ation. Jean Dubuffet, \\u00e0 travers le mouvement de l\\u2019<strong>art brut<\\/strong>, s\\u2019est int\\u00e9ress\\u00e9 \\u00e0 ces cr\\u00e9ations \\u201chors normes\\u201d.<\\/p><\\/li><li><p><strong>Approches institutionnelles, m\\u00e9diations en institution, art en h\\u00f4pital<\\/strong><br>Certains courants privil\\u00e9gient l\\u2019int\\u00e9gration de l\\u2019art dans le champ m\\u00e9dico-social, hospitalier ou institutionnel, pour offrir des ateliers collectifs, des cycles de m\\u00e9diation artistique, des expositions participatives, ou des interventions en milieu hospitalier. La m\\u00e9diation artistique y joue un r\\u00f4le de lien entre le soin, la cr\\u00e9ation et la vie sociale.<\\/p><\\/li><\\/ol>","title":"<p>Les grands <strong>courants et approches<\\/strong> de l\\u2019art-th\\u00e9rapie<\\/p>","anchor":"courants","animate":false,"block_id":"0419501b-1609-483c-ba05-1bdf0e40f4b9","is_hidden":false,"description":"Il n\\u2019y a pas \\u201cune\\u201d seule fa\\u00e7on de pratiquer l\\u2019art-th\\u00e9rapie : plusieurs approches coexistent, selon les r\\u00e9f\\u00e9rences th\\u00e9oriques, les choix m\\u00e9thodologiques, le r\\u00f4le donn\\u00e9 au m\\u00e9dium artistique et le positionnement du praticien. Voici quelques grands courants :","image_right":false,"couche_blanc":null,"photo_config":{"url":null,"display_type":null},"style_listes":"alternance","secondary_text":false,"section_styles":{"couche_blanc":null,"background_image":null,"direction_couleur":null},"background_image":null,"couleur_primaire":"primary","direction_couleur":null,"photo_display_type":null,"afficher_separateur":false},"type":"content"},{"data":{"photo":null,"texts":"<h4>Usages contemporains<\\/h4><ul><li><p>Elle est utilis\\u00e9e dans des contextes vari\\u00e9s : h\\u00f4pitaux psychiatriques, centres de r\\u00e9\\u00e9ducation, \\u00e9coles, maisons de retraite, centres de prise en charge du cancer, services de soutien psychologique, associations de bien-\\u00eatre, lib\\u00e9ral.<\\/p><\\/li><li><p>Elle s\\u2019adresse \\u00e0 des publics divers : enfants, adolescents, adultes, personnes \\u00e2g\\u00e9es, personnes en souffrance psychique, usagers de soins psychiatriques, personnes en situation de handicap, etc.<\\/p><\\/li><li><p>Elle est parfois propos\\u00e9e comme compl\\u00e9ment d\\u2019autres th\\u00e9rapeutiques \\u2014 psychoth\\u00e9rapie, th\\u00e9rapies corporelles, soutien psychologique \\u2014 notamment pour \\u201cdonner forme\\u201d \\u00e0 ce qu\\u2019il est d\\u00e9licat de nommer avec des mots.<\\/p><\\/li><li><p>Du c\\u00f4t\\u00e9 de la recherche, l\\u2019int\\u00e9r\\u00eat scientifique pour l\\u2019impact des arts sur la sant\\u00e9 mentale et le bien-\\u00eatre est en croissance \\u2014 plusieurs \\u00e9tudes et m\\u00e9ta-analyses explorent les effets (r\\u00e9duction du stress, soutien dans les d\\u00e9mences ou les pathologies somatiques, gestion de la douleur, etc.). Toutefois, selon les sources, la rigueur m\\u00e9thodologique reste un enjeu, et les conclusions restent conditionnelles.<\\/p><\\/li><\\/ul><p>L\\u2019Organisation mondiale de la sant\\u00e9 (OMS) reconna\\u00eet le r\\u00f4le potentiel des arts (dont l\\u2019art-th\\u00e9rapie) dans la promotion de la sant\\u00e9 mentale et physique, notamment en pr\\u00e9vention, mais souligne que davantage de recherches contr\\u00f4l\\u00e9es sont n\\u00e9cessaires.<\\/p><h4>D\\u00e9fis &amp; limites<\\/h4><ul><li><p>Manque d\\u2019uniformit\\u00e9 : il n\\u2019existe pas de r\\u00e9glementation, de reconnaissance professionnelle ou de \\u201clicence\\u201d unique dans la plupart des pays, ce qui peut engendrer des disparit\\u00e9s de formation et de pratiques.<\\/p><\\/li><li><p>\\u00c9valuation scientifique : il est souvent difficile de mener des recherches randomis\\u00e9es contr\\u00f4l\\u00e9es avec des crit\\u00e8res standardis\\u00e9s sur les approches artistiques, ce qui limite la validation empirique de l\\u2019efficacit\\u00e9 dans certains troubles.<\\/p><\\/li><li><p>Risque d\\u2019appropriation r\\u00e9duite \\u00e0 \\u201ctechnique\\u201d : certains praticiens d\\u00e9noncent le danger de \\u201ctechniciser\\u201d l\\u2019art-th\\u00e9rapie (r\\u00e9duire l\\u2019art \\u00e0 un outil psychologique) et de perdre sa dimension expressive, esth\\u00e9tique, po\\u00e9tique. (Cf. \\u201cprocess and product\\u201d dans les r\\u00e9flexions sur l\\u2019art-th\\u00e9rapeute)<\\/p><\\/li><li><p>Adaptation des mat\\u00e9riaux et du cadre selon les publics (enfants, personnes en difficult\\u00e9 motrice, en situation de handicap) : le praticien doit sans cesse ajuster son dispositif.<\\/p><\\/li><li><p>Tensions entre approche libre vs approche directrice : trouver le juste compromis entre accompagnement et libert\\u00e9 de cr\\u00e9ation.<\\/p><\\/li><\\/ul><h4>Perspectives<\\/h4><ul><li><p>D\\u00e9veloppement de <strong>protocoles de recherche interdisciplinaires<\\/strong> (neurosciences, psychologie, arts, sant\\u00e9 publique).<\\/p><\\/li><li><p>Int\\u00e9gration dans les politiques de sant\\u00e9, comme approche compl\\u00e9mentaire non pharmacologique dans les troubles chroniques ou les soins palliatifs.<\\/p><\\/li><li><p>Innovations dans les m\\u00e9diations (num\\u00e9rique, arts num\\u00e9riques, r\\u00e9alit\\u00e9 virtuelle, vid\\u00e9o, collage digital).<\\/p><\\/li><li><p>Renforcement de la formation, de la supervision, de l\\u2019\\u00e9thique de la pratique et de la reconnaissance institutionnelle.<\\/p><\\/li><li><p>Rencontres entre les mondes de l\\u2019art (galeries, expositions, art \\u201coutsider\\u201d) et de la sant\\u00e9, comme dans des lieux hybrides (mus\\u00e9es-soins, ateliers hospitaliers ouverts au public).<\\/p><\\/li><\\/ul>","title":"<p>L\\u2019art-th\\u00e9rapie aujourd\\u2019hui : usages, d\\u00e9fis et perspectives<\\/p>","anchor":"aujourdhui","animate":false,"block_id":"812b7668-8233-456b-a75d-e34c4cecbb9e","is_hidden":false,"description":null,"image_right":false,"couche_blanc":"aucun","photo_config":{"url":null,"display_type":null},"style_listes":"alternance","secondary_text":false,"section_styles":{"couche_blanc":null,"background_image":null,"direction_couleur":null},"background_image":null,"couleur_primaire":"secondary","direction_couleur":"aucun","photo_display_type":null,"afficher_separateur":false,"section_background_image":null},"type":"content"}]',
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
    'order' => 3,
    'published_at' => NULL,
    'created_at' => '2025-10-03 11:27:32',
    'updated_at' => '2025-10-09 08:34:57',
  ),
);
        
        foreach ($pages as $page) {
            DB::table('cms_pages')->insert($page);
        }
    }
}