<?php
/**
 * Contains the TaxonTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-08-27
 *
 */

namespace Vanilo\Category\Tests;

use Vanilo\Category\Models\Taxon;
use Vanilo\Category\Models\Taxonomy;

class TaxonTest extends TestCase
{
    /** @test */
    public function taxons_must_belong_to_a_taxonomy()
    {
        $this->expectExceptionMessageRegExp('/NOT NULL constraint failed: taxons\.taxonomy_id/');

        Taxon::create();
    }

    /** @test */
    public function taxons_must_have_a_name()
    {
        $taxonomy = Taxonomy::create(['name' => 'Category']);

        $this->expectExceptionMessageRegExp('/NOT NULL constraint failed: taxons\.name/');

        Taxon::create(['taxonomy_id' => $taxonomy->id]);
    }

    /** @test */
    public function slug_is_autogenerated_from_name()
    {
        $taxonomy = Taxonomy::create(['name' => 'Category']);

        $taxon = Taxon::create(['taxonomy_id' => $taxonomy->id, 'name' => 'Example Taxon']);

        $this->assertEquals('example-taxon', $taxon->slug);
    }

    /** @test */
    public function taxons_belong_to_a_taxonomy()
    {
        $taxonomy = Taxonomy::create(['name' => 'Category']);

        $taxon = Taxon::create(['taxonomy_id' => $taxonomy->id, 'name' => 'Taxon']);

        $this->assertInstanceOf(Taxonomy::class, $taxon->taxonomy);
        $this->assertEquals($taxonomy->id, $taxon->taxonomy->id);
    }

    /** @test */
    public function taxons_parent_is_optional()
    {
        $taxonomy = Taxonomy::create(['name' => 'Category']);

        $taxon = Taxon::create(['name' => 'Parent', 'taxonomy_id' => $taxonomy->id]);

        $this->assertNull($taxon->parent);
    }

    /** @test */
    public function taxons_can_have_a_parent()
    {
        $taxonomy = Taxonomy::create(['name' => 'Category']);

        $taxon = Taxon::create(['name' => 'Parent', 'taxonomy_id' => $taxonomy->id]);

        $child = Taxon::create([
            'name' => 'Child',
            'parent_id' => $taxon->id,
            'taxonomy_id' => $taxonomy->id
        ]);

        $this->assertEquals($taxon->id, $child->parent->id);
    }

    /** @test */
    public function taxons_can_have_children()
    {
        $taxonomy = Taxonomy::create(['name' => 'Category']);

        $taxon = Taxon::create(['name' => 'Parent', 'taxonomy_id' => $taxonomy->id]);

        Taxon::create([
            'name' => 'Child 1',
            'parent_id' => $taxon->id,
            'taxonomy_id' => $taxonomy->id
        ]);

        Taxon::create([
            'name' => 'Child 2',
            'parent_id' => $taxon->id,
            'taxonomy_id' => $taxonomy->id
        ]);

        Taxon::create([
            'name' => 'Child 3',
            'parent_id' => $taxon->id,
            'taxonomy_id' => $taxonomy->id
        ]);

        $this->assertCount(3, $taxon->children);
    }
}
