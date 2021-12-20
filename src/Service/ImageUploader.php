<?php

namespace App\Service;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageUploader
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * Methode permettant de déplacer une image issue d'un formulaire
     * vers un dossier uploads
     *
     * @param Form $form
     * @param string $fieldName
     * @return string|null
     */
    public function upload(Form $form, string $fieldName, $uploadFolder = null)
    {
        // if ($uploadFolder) {
        //     $uploadFolder = $uploadFolder;
        // } else {
        //     $uploadFolder = $_ENV['UPLOAD_FOLDER'];
        // }
        // Opérateur de coalescence null ==> depuis PHP 7
        // $uploadFolder = $uploadFolder ?? $_ENV['UPLOAD_FOLDER'];

        // Si la variable $uploadFolder n'est pas définie, 
        // on utilise la variable d'environnement UPLOAD_FODLER
        // définie dans le fichier .env    
        if ($uploadFolder === null) {
            $uploadFolder = $_ENV['UPLOAD_FOLDER'];
        }

        /** @var UploadedFile $imageFile */
        // On récupère le fichier "physique"
        // $form->get('image')->getData();
        $imageFile = $form->get($fieldName)->getData();

        // Si on a bien une image à uploader, on va pouvoir
        // la déplacer dans le dossier uploads
        if ($imageFile) {
            $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $this->slugger->slug($originalFileName);

            // Ms-social-cdcd4177211.jpeg
            $newFileName = $safeFileName . '-' . uniqid() . '.' . $imageFile->guessExtension();

            // On déplace le fichier du dossier temporaire (/tmp/)
            // vers le dossier uploads
            try {
                // Si le déplacement s'est bien passé, on va pouvoir
                // passer à la mise à jour de l'entité
                // $uploadFolder = 'uploads/images'
                $imageFile->move($uploadFolder, $newFileName);

                // A la fin de l'upload, on retourne le nom de l'image
                return $newFileName;
            } catch (FileException $e) {
                // Sinon, on affiche une erreur
                // Envoyer un Email à l'adminstrateur
                // Envoyer un message au client
                dump($e);
            }
        }

        // Aucune image à uploader...on retourne null
        return null;
    }
}
