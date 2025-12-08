<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Arsip;
use Faker\Factory as Faker;

class ArsipSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_US');

        // kategori dari filter
        $kategoriList = [
            'Teknologi',
            'Sains',
            'Sejarah',
            'Hukum & Politik',
            'Kesehatan',
            'Komputer & Informatika'
        ];

        // Penulis nusantara campur internasional biar nggak terasa "lokal"
        $authors = [
            'Al-Khwarizmi', 'Ibn Sina', 'Ibn al-Haytham', 'Al-Farabi', 'Ibn Rushd',
            'Al-Biruni', 'Hunayn ibn Ishaq', 'Nasir al-Din al-Tusi', 'Al-Kindi',
            'Zhang Heng', 'Aryabhata', 'Hipokrates', 'Herodotus',
            'Galen of Pergamon', 'Euclid', 'Ptolemy',
        ];

        // Template judul biar kelihatan seperti manuskrip/perpustakaan modern
        $titlePatterns = [
            'Treatise on %s',
            'Compendium of %s',
            'Book of %s',
            'Commentary on %s',
            'Chronicles of %s',
            'Foundations of %s',
            'The Principles of %s',
            'The Study of %s',
        ];

        // Bidang ilmu
        $subjects = [
            'Astronomy', 'Medicine', 'Geometry', 'Governance', 'Philosophy',
            'Natural Science', 'Mathematics', 'Navigation', 'Herbal Studies',
            'Political Theory', 'Logic', 'Ethics', 'Anatomy',
            'Algebra', 'Mechanical Arts', 'Ancient History'
        ];

        $raks = ['A', 'B', 'C', 'D'];
        $baris = ['1', '2', '3', '4', '5'];

        for ($i = 0; $i < 40; $i++) {

            // Buat judul berdasarkan template + subject
            $pattern = $faker->randomElement($titlePatterns);
            $subject = $faker->randomElement($subjects);
            $judul = sprintf($pattern, $subject);

            $pengarang = $faker->randomElement($authors);

            Arsip::create([
                'judul' => $judul,
                'pengarang' => $pengarang,
                'abstrak' => $faker->paragraph(8, true),
                'lokasi_rak' => $faker->randomElement($raks),
                'lokasi_baris' => $faker->randomElement($baris),
                'kategori' => $faker->randomElement($kategoriList),
                'thumbnail_path' => null,
                'file_dokumen_path' => null,
                'slug' => Str::slug($judul . '-' . $i),
                'published_at' => $faker->dateTimeBetween('1970-01-01', 'now'),
            ]);
        }
    }
}
