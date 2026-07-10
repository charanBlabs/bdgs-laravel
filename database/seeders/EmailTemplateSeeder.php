<?php



namespace Database\Seeders;



use App\Models\BdgsEmailTemplate;

use Database\Seeders\Support\EmailTemplatePlaceholderNormalizer;

use Database\Seeders\Support\LegacyEmailTemplateMapper;

use Database\Seeders\Support\OldEmailTemplateSqlParser;

use Illuminate\Database\Seeder;

use RuntimeException;



class EmailTemplateSeeder extends Seeder

{

    public function run(): void

    {

        $sqlPath = database_path('seeders/data/bdgs_email_templates.sql');

        if (! is_readable($sqlPath)) {

            throw new RuntimeException("Legacy email template SQL dump not found at {$sqlPath}");

        }



        $legacyRows = OldEmailTemplateSqlParser::parseFile($sqlPath);



        foreach (LegacyEmailTemplateMapper::definitions() as $slug => $definition) {

            $template = $this->buildTemplate($slug, $definition, $legacyRows);



            BdgsEmailTemplate::query()->updateOrCreate(

                ['slug' => $slug],

                $template

            );

        }

    }



    /**

     * @param  array{name: string, source: string|null, is_active: bool, category: string}  $definition

     * @param  array<string, array<string, mixed>>  $legacyRows

     * @return array<string, mixed>

     */

    private function buildTemplate(string $slug, array $definition, array $legacyRows): array

    {

        if ($definition['source'] === null) {

            $builtIn = LegacyEmailTemplateMapper::builtInTemplate($slug);

            if ($builtIn === null) {

                throw new RuntimeException("Missing built-in template definition for slug [{$slug}]");

            }



            return [

                'slug' => $slug,

                'name' => $definition['name'],

                'subject' => $builtIn['subject'],

                'body_html' => $builtIn['body_html'],

                'body_text' => null,

                'variables' => $builtIn['variables'],

                'is_active' => $definition['is_active'],

            ];

        }



        $legacy = $legacyRows[$definition['source']] ?? null;

        if ($legacy === null) {

            throw new RuntimeException(

                "Legacy template [{$definition['source']}] not found in SQL dump for slug [{$slug}]"

            );

        }



        $normalized = EmailTemplatePlaceholderNormalizer::normalize(

            $legacy['email_subject'],

            $legacy['email_body']

        );



        return [

            'slug' => $slug,

            'name' => $definition['name'],

            'subject' => $normalized['subject'],

            'body_html' => $normalized['body_html'],

            'body_text' => null,

            'variables' => $normalized['variables'],

            'is_active' => $definition['is_active'],

        ];

    }

}

