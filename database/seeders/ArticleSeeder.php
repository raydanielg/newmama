<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logo = 'LOGO-MALKIA-KONNECT-removebg-preview.png';
        $articles = [
            [
                'title' => 'Building Secure Attachment: The Foundation of Infant Emotional Health',
                'category' => 'Newborns (0-12 months)',
                'age_range' => '4-6 months',
                'image' => $logo,
                'published_at' => '2026-05-01',
                'is_featured' => true,
                'content' => '<p>Attachment is the deep and enduring emotional bond that connects one person to another across time and space. In infants, this bond is primarily formed with their primary caregivers, usually the mother. Secure attachment is not just about "love"; it\'s about a physiological state of safety that allows a child\'s brain to grow optimally.</p>
                <h3>Why Secure Attachment Matters</h3>
                <p>Secure attachment provides a safe base from which children can explore the world. It is the foundation for healthy emotional development, social skills, and even cognitive growth. When a baby knows that their cries will be answered and their needs met, they learn that the world is a safe place.</p>
                <ul>
                    <li><strong>Emotional Regulation:</strong> Children with secure attachment are better at managing their emotions later in life.</li>
                    <li><strong>Trust in Others:</strong> It forms the template for all future relationships.</li>
                    <li><strong>Resilience:</strong> Securely attached infants tend to be more resilient when facing stress or trauma in adulthood.</li>
                </ul>
                <h3>Practical Steps to Build Attachment</h3>
                <p>You don\'t have to be perfect. Building attachment is about the "rupture and repair" process. If you miss a cue, it\'s okay—just try again. Eye contact, skin-to-skin contact, and responsive feeding are all vital tools in this journey.</p>
                <p>By responding consistently to your baby\'s needs, you are building a lifetime of emotional health. Remember, you cannot "spoil" a baby with too much love or attention in these early months.</p>',
            ],
            [
                'title' => 'Navigating Common Health Concerns in Infants: A Comprehensive Guide',
                'category' => 'Newborns (0-12 months)',
                'age_range' => '0-3 months',
                'image' => $logo,
                'published_at' => '2026-05-01',
                'is_featured' => true,
                'content' => '<p>The first few months of a baby\'s life are filled with joy, but also with many questions about their health. Understanding common concerns can help parents feel more confident and reduce unnecessary anxiety. Most of the issues babies face are part of their normal adjustment to life outside the womb.</p>
                <h3>Common Issues Parents Face</h3>
                <p>From diaper rash to colic, babies experience various minor health issues that are part of normal development. Colic, for instance, often peaks at 6 weeks and subsides by 3-4 months. While stressful, it is usually not a sign of a medical problem.</p>
                <blockquote>"Parenthood is a learning curve. Trust your instincts, but never hesitate to seek professional advice when something feels off." — Malkia Medical Team</blockquote>
                <h3>When to Call the Doctor</h3>
                <p>Learning the signs of when to call a doctor is an essential part of early parenthood. Look out for high fever (above 38°C for newborns), signs of dehydration (fewer than 6 wet diapers a day), or unusual lethargy. If your baby is not feeding well or seems excessively irritable, a quick check-up is always the safest route.</p>
                <p>Always consult with your pediatrician if you are concerned about your baby\'s health or behavior. Your peace of mind is important too!</p>',
            ],
            [
                'title' => 'Developmental Milestones in the First Year: Supporting Your Baby\'s Growth',
                'category' => 'Newborns (0-12 months)',
                'age_range' => '0-3 months',
                'image' => $logo,
                'published_at' => '2026-05-01',
                'is_featured' => true,
                'content' => '<p>Every baby develops at their own pace, but there are certain milestones that most babies reach during their first year. These milestones include physical, social, and cognitive developments. It\'s important to remember that these are ranges, not strict deadlines.</p>
                <h3>Key Milestones by Quarter</h3>
                <p>In the first three months, your baby starts to smile at people and can briefly calm themselves. By six months, they may start to roll over and respond to their own name. The second half of the year is usually when the "big" moves happen—crawling, pulling to stand, and potentially those first precious words.</p>
                <p>You can support this development through play, tummy time, and lots of interaction. Reading to your baby from day one is one of the best ways to support language development.</p>
                <h3>Tummy Time: Why It Matters</h3>
                <p>Tummy time helps build the neck and shoulder muscles needed for sitting and crawling. Start with just a few minutes a day and increase as your baby gets stronger. Make it fun by getting down on the floor with them!</p>',
            ],
            [
                'title' => 'Infant Feeding Milestones: From Exclusive Milk to First Solids',
                'category' => 'Newborns (0-12 months)',
                'age_range' => '0-3 months',
                'image' => $logo,
                'published_at' => '2026-05-01',
                'is_featured' => true,
                'content' => '<p>Feeding is one of the most important aspects of caring for your newborn. Whether breastfeeding or using formula, ensuring your baby gets the right nutrition is vital for their brain and body development. For the first six months, exclusive milk feeding is generally recommended by health experts.</p>
                <h3>Starting Solids: The Signs of Readiness</h3>
                <p>Around six months, most babies are ready to start exploring solid foods alongside their milk. It\'s an exciting but messy new chapter! Don\'t rush it—look for these signs of readiness:</p>
                <ul>
                    <li>Sitting up with little or no support.</li>
                    <li>Good head control.</li>
                    <li>Showing interest in what you are eating.</li>
                    <li>The "tongue-thrust reflex" has disappeared.</li>
                </ul>
                <h3>The First Tastes</h3>
                <p>Start with single-grain cereals or pureed vegetables. Introduce one new food at a time and wait a few days to check for any allergic reactions. The goal at this stage is exploration and learning new textures, not replacing milk entirely.</p>
                <p>Keep the environment calm and positive. If they reject a food, don\'t worry—it can take up to 15 tries for a baby to accept a new flavor!</p>',
            ],
        ];

        foreach ($articles as $article) {
            $catId = DB::table('article_categories')->where('name', $article['category'])->value('id');
            
            DB::table('articles')->updateOrInsert(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'slug' => Str::slug($article['title']),
                    'category_id' => $catId,
                    'category' => $article['category'],
                    'age_range' => $article['age_range'],
                    'image' => $article['image'],
                    'excerpt' => Str::limit(strip_tags($article['content']), 120),
                    'content' => $article['content'],
                    'published_at' => $article['published_at'],
                    'is_featured' => $article['is_featured'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
