<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Profile;

class ProfileFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $profiles = [
            ['nameRS' => 'LinkedIn', 'url' => 'https://www.linkedin.com/in/johndoe'],
            ['nameRS' => 'GitHub', 'url' => 'https://github.com/johndoe'],
            ['nameRS' => 'Twitter', 'url' => 'https://twitter.com/johndoe'],
            ['nameRS' => 'Facebook', 'url' => 'https://www.facebook.com/johndoe'],
            ['nameRS' => 'Instagram', 'url' => 'https://www.instagram.com/johndoe'],
            ['nameRS' => 'YouTube', 'url' => 'https://www.youtube.com/c/johndoe'],
            ['nameRS' => 'Stack Overflow', 'url' => 'https://stackoverflow.com/users/123456/johndoe'],
            ['nameRS' => 'Behance', 'url' => 'https://www.behance.net/johndoe'],
            ['nameRS' => 'Dribbble', 'url' => 'https://dribbble.com/johndoe'],
            ['nameRS' => 'Medium', 'url' => 'https://medium.com/@johndoe'],
            ['nameRS' => 'Reddit', 'url' => 'https://www.reddit.com/user/johndoe'],
            ['nameRS' => 'Pinterest', 'url' => 'https://www.pinterest.com/johndoe'],
            ['nameRS' => 'Quora', 'url' => 'https://www.quora.com/profile/John-Doe'],
            ['nameRS' => 'Vimeo', 'url' => 'https://vimeo.com/johndoe'],
            ['nameRS' => 'Twitch', 'url' => 'https://www.twitch.tv/johndoe'],
            ['nameRS' => 'TikTok', 'url' => 'https://www.tiktok.com/@johndoe'],
            ['nameRS' => 'DeviantArt', 'url' => 'https://www.deviantart.com/johndoe'],
            ['nameRS' => 'Goodreads', 'url' => 'https://www.goodreads.com/user/show/1234567-johndoe'],
            ['nameRS' => 'Snapchat', 'url' => 'https://www.snapchat.com/add/johndoe'],
            ['nameRS' => 'Discord', 'url' => 'https://discord.com/users/123456789012345678'],
        ];
        
        foreach ($profiles as $profileData) {
            $profile = new Profile();
            $profile->setNameRS($profileData['nameRS'])
                    ->setUrl($profileData['url']);

            $manager->persist($profile);
        }

        $manager->flush();
    }
}
