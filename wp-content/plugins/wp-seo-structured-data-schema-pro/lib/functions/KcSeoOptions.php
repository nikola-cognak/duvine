<?php

class KcSeoOptions {


	static function getSchemaTypes() {
		$author_url = '';
		$author     = get_userdata( get_current_user_id() );
		if ( $author && is_object( $author ) ) {
			$author_url = $author->user_url;
		}
		$schemas = [

			'blog_posting'         => [
				'title'  => __( 'Blog Posting', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'           => [
						'type' => 'checkbox',
					],
					'headline'         => [
						'title'    => __( 'Headline', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Blog posting title', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'mainEntityOfPage' => [
						'title'    => __( 'Page URL', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'url',
						'desc'     => __( 'The canonical URL of the article page', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'author'           => [
						'title'    => __( 'Author name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Author display name', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'author_url'       => [
						'title'    => __( 'Author URL', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'url',
						'default'  => $author_url,
						'required' => true,
					],
					'image'            => [
						'title'    => __( 'Featured Image', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'desc'     => __( 'The representative image of the article. Only a marked-up image that directly belongs to the article should be specified.<br> Images should be at least 696 pixels wide. <br>Images should be in .jpg, .png, or. gif format.', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'datePublished'    => [
						'title'    => __( 'Published date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'class'    => 'kcseo-date',
						'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'dateModified'     => [
						'title'    => __( 'Modified date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'class'    => 'kcseo-date',
						'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'publisher'        => [
						'title'    => __( 'Publisher', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Publisher name or Organization name', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'publisherImage'   => [
						'title'    => __( 'Publisher Logo', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'desc'     => __( 'Logos should have a wide aspect ratio, not a square icon.<br>Logos should be no wider than 600px, and no taller than 60px.<br>Always retain the original aspect ratio of the logo when resizing. Ideally, logos are exactly 60px tall with width <= 600px. If maintaining a height of 60px would cause the width to exceed 600px, downscale the logo to exactly 600px wide and reduce the height accordingly below 60px to maintain the original aspect ratio.<br>', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'description'      => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Short description. New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],
					'articleBody'      => [
						'title' => __( 'Article body', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Article content. New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],
					'video'            => [
						'title'  => __( 'Video', 'wp-seo-structured-data-schema-pro' ),
						'type'   => 'group',
						'fields' => [
							'video_heading' => [
								'type'  => 'heading',
								'title' => __( 'Video', 'wp-seo-structured-data-schema-pro' ),
							],
							'name'          => [
								'type'  => 'text',
								'title' => esc_html__( 'Name', 'wp-seo-structured-data-schema-pro' ),
							],
							'description'   => [
								'type'  => 'textarea',
								'title' => esc_html__( 'Description', 'wp-seo-structured-data-schema-pro' ),
							],
							'thumbnailUrl'  => [
								'type'  => 'image',
								'title' => esc_html__( 'Image', 'wp-seo-structured-data-schema-pro' ),
							],
							'contentUrl'    => [
								'type'  => 'url',
								'title' => esc_html__( 'Content URL', 'wp-seo-structured-data-schema-pro' ),
							],
							'embedUrl'      => [
								'type'  => 'url',
								'title' => esc_html__( 'Embed URL', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'A URL pointing to the actual video media file. This file should be in .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv, or other video file format.', 'wp-seo-structured-data-schema-pro' ),
							],
							'uploadDate'    => [
								'type'     => 'text',
								'title'    => esc_html__( 'Upload date', 'wp-seo-structured-data-schema-pro' ),
								'class'    => 'rtrs-date',
								'required' => true,
								'desc'     => esc_html__( 'Like this: 2021-08-25 14:20:00', 'wp-seo-structured-data-schema-pro' ),
							],
							'duration'      => [
								'type'  => 'text',
								'title' => esc_html__( 'Duration', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'Runtime of the movie in ISO 8601 format (for example, "PT2H22M" (142 minutes)).', 'wp-seo-structured-data-schema-pro' ),
							],
						],
					],
					'audio'            => [
						'title'  => __( 'Audio', 'wp-seo-structured-data-schema-pro' ),
						'type'   => 'group',
						'fields' => [
							'audio_heading'  => [
								'type'  => 'heading',
								'title' => __( 'Audio', 'wp-seo-structured-data-schema-pro' ),
							],
							'name'           => [
								'type'  => 'text',
								'title' => esc_html__( 'Name', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'The title of the audio', 'wp-seo-structured-data-schema-pro' ),
							],
							'description'    => [
								'type'  => 'textarea',
								'title' => esc_html__( 'Description', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'The short description of the audio', 'wp-seo-structured-data-schema-pro' ),
							],
							'duration'       => [
								'type'  => 'text',
								'title' => esc_html__( 'Duration', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'The duration of the audio in ISO 8601 format.(PT1M33S)', 'wp-seo-structured-data-schema-pro' ),
							],
							'contentUrl'     => [
								'type'        => 'url',
								'title'       => esc_html__( 'Content URL', 'wp-seo-structured-data-schema-pro' ),
								'placeholder' => esc_html__( 'URL', 'wp-seo-structured-data-schema-pro' ),
								'desc'        => esc_html__( 'A URL pointing to the actual audio media file. This file should be in .mp3, .wav, .mpc or other audio file format.', 'wp-seo-structured-data-schema-pro' ),
							],
							'encodingFormat' => [
								'type'  => 'text',
								'title' => esc_html__( 'Encoding Format', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( "The encoding format of audio like: 'audio/mpeg'", 'wp-seo-structured-data-schema-pro' ),
							],
						],
					],
				],
			],
			'aggregate_rating'     => [
				'title'  => __( 'Aggregate Ratings', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'      => [
						'type' => 'checkbox',
					],
					'schema_type' => [
						'title'    => __( 'Schema type', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'schema_type',
						'required' => true,
						'options'  => self::getSiteTypes(),
						'empty'    => 'Select one',
						'desc'     => __( 'Use the most appropriate schema type for what is being reviewed.', 'wp-seo-structured-data-schema-pro' ),
					],
					'name'        => [
						'title'    => __( 'Name of the item', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'The item that is being rated.', 'wp-seo-structured-data-schema-pro' ),
					],
					'image'       => [
						'title'       => 'Image',
						'type'        => 'image',
						'required'    => true,
						'holderClass' => 'kSeo-hidden aggregate-except-organization-holder',
					],
					'priceRange'  => [
						'title'       => 'Price Range (Recommended)',
						'type'        => 'text',
						'holderClass' => 'kSeo-hidden aggregate-except-organization-holder',
						'desc'        => __( 'The price range of the business, for example $$$.', 'wp-seo-structured-data-schema-pro' ),
					],
					'telephone'   => [
						'title'       => 'Telephone (Recommended)',
						'type'        => 'text',
						'holderClass' => 'kSeo-hidden aggregate-except-organization-holder',
					],
					'address'     => [
						'title'       => 'Address (Recommended)',
						'type'        => 'text',
						'holderClass' => 'kSeo-hidden aggregate-except-organization-holder',
					],
					'description' => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Description for thr review', 'wp-seo-structured-data-schema-pro' ),
					],
					'ratingCount' => [
						'title'    => __( 'Rating Count', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'number',
						'attr'     => 'step="any"',
						'required' => true,
						'desc'     => __( "The total number of ratings for the item on your site. <span class='required'>* At least one of ratingCount or reviewCount is required.</span>", 'wp-seo-structured-data-schema-pro' ),
					],
					'reviewCount' => [
						'title'    => __( 'Review Count', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'number',
						'attr'     => 'step="any"',
						'required' => true,
						'desc'     => __( 'Specifies the number of people who provided a review with or without an accompanying rating. At least one of ratingCount or reviewCount is required.', 'wp-seo-structured-data-schema-pro' ),
					],
					'ratingValue' => [
						'title'    => __( 'Rating Value', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'number',
						'attr'     => 'step="any"',
						'required' => true,
						'desc'     => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'bestRating'  => [
						'title'    => __( 'Best Rating', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'number',
						'attr'     => 'step="any"',
						'required' => true,
						'desc'     => __( "The highest value allowed in this rating system. <span class='required'>* Required if the rating system is not a 5-point scale.</span> If bestRating is omitted, 5 is assumed.", 'wp-seo-structured-data-schema-pro' ),
					],
					'worstRating' => [
						'title'    => __( 'Worst Rating', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'number',
						'attr'     => 'step="any"',
						'required' => true,
						'desc'     => __( "The lowest value allowed in this rating system. <span class='required'>* Required if the rating system is not a 5-point scale.</span> If worstRating is omitted, 1 is assumed.", 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'article'              => [
				'title'  => __( 'Article', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'              => [
						'type' => 'checkbox',
					],
					'headline'            => [
						'title'    => __( 'Headline', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Article title', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'mainEntityOfPage'    => [
						'title'    => __( 'Page URL', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'url',
						'desc'     => __( 'The canonical URL of the article page', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'author'              => [
						'title'    => __( 'Author Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Author display name', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'author_url'          => [
						'title'    => __( 'Author URL', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'url',
						'default'  => $author_url,
						'required' => true,
					],
					'image'               => [
						'title'    => __( 'Featured Image', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'required' => true,
						'desc'     => __( 'Images should be at least 696 pixels wide.<br>Images should be in .jpg, .png, or. gif format.', 'wp-seo-structured-data-schema-pro' ),
					],
					'datePublished'       => [
						'title'    => __( 'Published date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'class'    => 'kcseo-date',
						'required' => true,
						'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'dateModified'        => [
						'title'    => __( 'Modified date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'class'    => 'kcseo-date',
						'required' => true,
						'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'publisher'           => [
						'title'    => __( 'Publisher', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Publisher name or Organization name', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'publisherImage'      => [
						'title'    => __( 'Publisher Logo', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'desc'     => __( 'Logos should have a wide aspect ratio, not a square icon.<br>Logos should be no wider than 600px, and no taller than 60px.<br>Always retain the original aspect ratio of the logo when resizing. Ideally, logos are exactly 60px tall with width <= 600px. If maintaining a height of 60px would cause the width to exceed 600px, downscale the logo to exactly 600px wide and reduce the height accordingly below 60px to maintain the original aspect ratio.<br>', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'description'         => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Short description. New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],
					'articleBody'         => [
						'title' => __( 'Article body', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Article content', 'wp-seo-structured-data-schema-pro' ),
					],
					'alternativeHeadline' => [
						'title' => __( 'Alternative headline', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'A secondary headline for the article.', 'wp-seo-structured-data-schema-pro' ),
					],
					'video'               => [
						'title'  => __( 'Video', 'wp-seo-structured-data-schema-pro' ),
						'type'   => 'group',
						'fields' => [
							'video_heading' => [
								'type'  => 'heading',
								'title' => __( 'Video', 'wp-seo-structured-data-schema-pro' ),
							],
							'name'          => [
								'type'  => 'text',
								'title' => esc_html__( 'Name', 'wp-seo-structured-data-schema-pro' ),
							],
							'description'   => [
								'type'  => 'textarea',
								'title' => esc_html__( 'Description', 'wp-seo-structured-data-schema-pro' ),
							],
							'thumbnailUrl'  => [
								'type'  => 'image',
								'title' => esc_html__( 'Image', 'wp-seo-structured-data-schema-pro' ),
							],
							'contentUrl'    => [
								'type'  => 'url',
								'title' => esc_html__( 'Content URL', 'wp-seo-structured-data-schema-pro' ),
							],
							'embedUrl'      => [
								'type'  => 'url',
								'title' => esc_html__( 'Embed URL', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'A URL pointing to the actual video media file. This file should be in .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv, or other video file format.', 'wp-seo-structured-data-schema-pro' ),
							],
							'uploadDate'    => [
								'type'     => 'text',
								'title'    => esc_html__( 'Upload date', 'wp-seo-structured-data-schema-pro' ),
								'class'    => 'rtrs-date',
								'required' => true,
								'desc'     => esc_html__( 'Like this: 2021-08-25 14:20:00', 'wp-seo-structured-data-schema-pro' ),
							],
							'duration'      => [
								'type'  => 'text',
								'title' => esc_html__( 'Duration', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'Runtime of the movie in ISO 8601 format (for example, "PT2H22M" (142 minutes)).', 'wp-seo-structured-data-schema-pro' ),
							],
						],
					],
					'audio'               => [
						'title'  => __( 'Audio', 'wp-seo-structured-data-schema-pro' ),
						'type'   => 'group',
						'fields' => [
							'audio_heading'  => [
								'type'  => 'heading',
								'title' => __( 'Audio', 'wp-seo-structured-data-schema-pro' ),
							],
							'name'           => [
								'type'  => 'text',
								'title' => esc_html__( 'Name', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'The title of the audio', 'wp-seo-structured-data-schema-pro' ),
							],
							'description'    => [
								'type'  => 'textarea',
								'title' => esc_html__( 'Description', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'The short description of the audio', 'wp-seo-structured-data-schema-pro' ),
							],
							'duration'       => [
								'type'  => 'text',
								'title' => esc_html__( 'Duration', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'The duration of the audio in ISO 8601 format.(PT1M33S)', 'wp-seo-structured-data-schema-pro' ),
							],
							'contentUrl'     => [
								'type'        => 'url',
								'title'       => esc_html__( 'Content URL', 'wp-seo-structured-data-schema-pro' ),
								'placeholder' => esc_html__( 'URL', 'wp-seo-structured-data-schema-pro' ),
								'desc'        => esc_html__( 'A URL pointing to the actual audio media file. This file should be in .mp3, .wav, .mpc or other audio file format.', 'wp-seo-structured-data-schema-pro' ),
							],
							'encodingFormat' => [
								'type'  => 'text',
								'title' => esc_html__( 'Encoding Format', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( "The encoding format of audio like: 'audio/mpeg'", 'wp-seo-structured-data-schema-pro' ),
							],
						],
					],
				],
			],
			'TechArticle'          => [
				'pro'    => false,
				'title'  => __( 'Tech Article', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'             => [
						'type' => 'checkbox',
					],
					'headline'           => [
						'title'    => __( 'Headline', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Article title', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],

					'mainEntityOfPage'   => [
						'title'    => __( 'Page URL', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'url',
						'desc'     => __( 'The canonical URL of the article page', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'author_type'        => [
						'title'       => __( 'Author Type', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'select',
						'recommended' => true,
						'empty'       => __( 'Select one', 'wp-seo-structured-data-schema-pro' ),
						'options'     => [
							'Person'       => 'Person',
							'Organization' => 'Organization',
						],
					],
					'author'             => [
						'title'    => __( 'Author Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Author display name', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'author_url'         => [
						'title'    => __( 'Author URL', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'url',
						'required' => true,
						'default'  => $author_url,
					],
					'author_description' => [
						'title' => __( 'Author Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Short description. New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],
					'image'              => [
						'title'    => __( 'Article Feature Image', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'required' => true,
						'desc'     => __( 'Images should be at least 696 pixels wide.<br>Images should be in .jpg, .png, or. gif format.', 'wp-seo-structured-data-schema-pro' ),
					],
					'datePublished'      => [
						'title'    => __( 'Published date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'class'    => 'kcseo-date',
						'required' => true,
						'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'dateModified'       => [
						'title'    => __( 'Modified date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'class'    => 'kcseo-date',
						'required' => true,
						'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'publisher'          => [
						'title'    => __( 'Publisher', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Publisher name or Organization name', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'publisherImage'     => [
						'title'    => __( 'Publisher Logo', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'desc'     => __( 'Logos should have a wide aspect ratio, not a square icon.<br>Logos should be no wider than 600px, and no taller than 60px.<br>Always retain the original aspect ratio of the logo when resizing. Ideally, logos are exactly 60px tall with width <= 600px. If maintaining a height of 60px would cause the width to exceed 600px, downscale the logo to exactly 600px wide and reduce the height accordingly below 60px to maintain the original aspect ratio.<br>', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'description'        => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Short description. New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],

					'articleBody'        => [
						'title' => __( 'Article body', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Article content', 'wp-seo-structured-data-schema-pro' ),
					],
					'keywords'           => [
						'title'    => __( 'Keywords', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Article title', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],

				],
			],
			'MedicalWebPage'       => [
				'pro'    => false,
				'title'  => __( 'Medical WebPage', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'            => [
						'type' => 'checkbox',
					],
					'headline'          => [
						'title'    => __( 'Headline', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Medical title', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'webpage_url'       => [
						'title' => __( 'Webpage url', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'url',
						'desc'  => __( 'Web Page Url', 'wp-seo-structured-data-schema-pro' ),
					],
					'specialty_url'     => [
						'title' => __( 'Specialty url', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'url',
						'desc'  => __( 'Specialty Url', 'wp-seo-structured-data-schema-pro' ),
					],
					'image'             => [
						'title'    => __( 'Image', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'required' => true,
					],
					// dfasdf.
					'datePublished'     => [
						'title' => __( 'Published date', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'dateModified'      => [
						'title' => __( 'Modified date', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'publisher'         => [
						'title' => __( 'Publisher', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Publisher name or Organization name', 'wp-seo-structured-data-schema-pro' ),
					],
					'publisherImage'    => [
						'title' => __( 'Publisher Logo', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'image',
						'desc'  => __( 'Logos should have a wide aspect ratio, not a square icon.<br>Logos should be no wider than 600px, and no taller than 60px.<br>Always retain the original aspect ratio of the logo when resizing. Ideally, logos are exactly 60px tall with width <= 600px. If maintaining a height of 60px would cause the width to exceed 600px, downscale the logo to exactly 600px wide and reduce the height accordingly below 60px to maintain the original aspect ratio.<br>', 'wp-seo-structured-data-schema-pro' ),
					],

					'lastreviewed'      => [
						'title' => __( 'Last Reviewed', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2021-12-25', 'wp-seo-structured-data-schema-pro' ),
					],

					'maincontentofpage' => [
						'title' => __( 'Main Content of Page', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'about'             => [
						'title' => __( 'About', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'description'       => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Short description. New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],
					'keywords'          => [
						'title' => __( 'Keywords', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],

				],
			],

			'CollectionPage'       => [
				'pro'    => false,
				'title'  => __( 'Collection Page', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'      => [
						'type' => 'checkbox',
					],
					'name'        => [
						'type'     => 'text',
						'title'    => esc_html__( 'Headline', 'wp-seo-structured-data-schema-pro' ),
						'desc'     => esc_html__( 'Title', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'webpage_url' => [
						'title' => __( 'Webpage url', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'url',
						'desc'  => __( 'Web Page Url', 'wp-seo-structured-data-schema-pro' ),
					],
					'description' => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Short description. New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],
					'image'       => [
						'title'    => __( 'Image', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'required' => true,
					],
					'itempage'    => [
						'type'      => 'group',
						'title'     => esc_html__( 'Item page', 'wp-seo-structured-data-schema-pro' ),
						'duplicate' => true,
						'fields'    => [
							'itempage-name'        => [
								'type'     => 'text',
								'title'    => esc_html__( 'Name', 'wp-seo-structured-data-schema-pro' ),
								'desc'     => esc_html__( 'Title', 'wp-seo-structured-data-schema-pro' ),
								'required' => true,
							],
							'itempage-description' => [
								'type'  => 'textarea',
								'title' => esc_html__( 'Description', 'wp-seo-structured-data-schema-pro' ),
							],
							'mainEntityOfPage'     => [
								'title' => __( 'Main Entity Page url', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'url',
							],
						],
					],

				],
			],

			'book'                 => [
				'title'  => __( 'Book', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'               => [
						'type' => 'checkbox',
					],
					'name'                 => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'datePublished'        => [
						'title'    => __( 'Published date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'class'    => 'kcseo-date',
						'required' => true,
						'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'author'               => [
						'title'    => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'author_sameAs'        => [
						'title'    => __( 'Author Same As profile link', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'attr'     => 'placeholder="https://facebook.com/example&#10;https://twitter.com/example"',
						'required' => true,
						'desc'     => __( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry', 'wp-seo-structured-data-schema-pro' ),
					],
					'bookFormat'           => [
						'title'    => __( 'Book Format', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'select',
						'options'  => [ 'EBook', 'Hardcover', 'Paperback', 'AudioBook' ],
						'required' => true,
					],
					'isbn'                 => [
						'title'    => __( 'ISBN', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'The ISBN of the tome. Use the ISBN of the print book instead if there is no ISBN for that edition, such as for a Kindle edition.', 'wp-seo-structured-data-schema-pro' ),
					],
					'workExample'          => [
						'title'    => __( 'Work Example', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'url'                  => [
						'title'    => __( 'URL', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'url',
						'required' => true,
						'desc'     => __( 'URL to the page on your site about the book. The page may list all available editions.', 'wp-seo-structured-data-schema-pro' ),
					],
					'sameAs'               => [
						'title'    => __( 'Same As', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'attr'     => 'placeholder="https://example.com/example&#10;https://example.com/example"',
						'required' => true,
						'desc'     => __( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry', 'wp-seo-structured-data-schema-pro' ),
					],
					'publisher'            => [
						'title' => __( 'Publisher', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'numberOfPages'        => [
						'title' => __( 'Number of Pages', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
					],
					'copyrightHolder'      => [
						'title' => __( 'Copyright Holder', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Holt, Rinehart and Winston', 'wp-seo-structured-data-schema-pro' ),
					],
					'copyrightYear'        => [
						'title' => __( 'Copyright Year', 'wp-seo-structured-data-schema-pro' ),
						'attr'  => 'step="any"',
						'type'  => 'number',
					],
					'description'          => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'genre'                => [
						'title' => __( 'Genre', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Educational Materials', 'wp-seo-structured-data-schema-pro' ),
					],
					'inLanguage'           => [
						'title' => __( 'Language', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'en-US', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_section'       => [
						'title' => __( 'Review', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
						'desc'  => __( 'To add review schema for this type, complete fields below and enable, others live blank.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_active'        => [
						'type' => 'checkbox',
					],
					'review_author'        => [
						'title'    => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'review_author_sameAs' => [
						'title'    => __( 'Author Same As profile link', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'attr'     => 'placeholder="https://facebook.com/example&#10;https://twitter.com/example"',
						'required' => true,
						'desc'     => __( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_body'          => [
						'title'    => __( 'Review body', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'required' => true,
						'desc'     => __( 'The actual body of the review.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_datePublished' => [
						'title' => __( 'Date of Published', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_ratingValue'   => [
						'title' => __( 'Rating value', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_bestRating'    => [
						'title' => __( 'Best rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The highest value allowed in this rating system.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_worstRating'   => [
						'title' => __( 'Worst rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The lowest value allowed in this rating system. * Required if the rating system is not on a 5-point scale. If worstRating is omitted, 1 is assumed.', 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'course'               => [
				'title'  => __( 'Course', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'               => [
						'type' => 'checkbox',
					],
					'name'                 => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'description'          => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
					],
					'provider'             => [
						'title' => __( 'Provider', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'courseMode'           => [
						'title' => __( 'Course Mode', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'attr'  => 'placeholder="One item per line like bellow"',
						'desc'  => __( 'Online</br>Onsite</br>Blended', 'wp-seo-structured-data-schema-pro' ),
					],
					'duration'             => [
						'title' => __( 'Course Duration', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Use the 8601 duration format. Ex: PT22H', 'wp-seo-structured-data-schema-pro' ),
					],
					'repeatFrequency'      => [
						'title' => __( 'Repeat Frequency', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Ex: Daily', 'wp-seo-structured-data-schema-pro' ),
					],
					'repeatCount'          => [
						'title' => __( 'Repeat Count', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Ex: 30', 'wp-seo-structured-data-schema-pro' ),
					],

					'startDate'            => [
						'title' => __( 'Start Date', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( '2017-10-16', 'wp-seo-structured-data-schema-pro' ),
					],
					'endDate'              => [
						'title' => __( 'End Date', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( '2017-10-16', 'wp-seo-structured-data-schema-pro' ),
					],
					'locationName'         => [
						'title'    => __( 'Location name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'locationAddress'      => [
						'title'    => __( 'Location address', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'image'                => [
						'title' => __( 'Course image', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'image',
					],
					'category'             => [
						'title'   => 'Category',
						'type'    => 'select',
						'empty'   => 'Select one',
						'default' => 'Paid',
						'options' => [
							'Paid',
							'Free',
							'Partially Free',
							'Subscription',
						],
						'desc'    => __( 'Select Course Category', 'wp-seo-structured-data-schema-pro' ),
					],
					'price'                => [
						'title' => __( 'Price', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
					],
					'priceCurrency'        => [
						'title' => __( 'Price Currency', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'USD', 'wp-seo-structured-data-schema-pro' ),
					],
					'availability'         => [
						'title'   => 'Availability',
						'type'    => 'select',
						'empty'   => 'Select one',
						'options' => [
							'http://schema.org/InStock'    => 'InStock',
							'http://schema.org/InStoreOnly' => 'InStoreOnly',
							'http://schema.org/OutOfStock' => 'OutOfStock',
							'http://schema.org/SoldOut'    => 'SoldOut',
							'http://schema.org/OnlineOnly' => 'OnlineOnly',
							'http://schema.org/LimitedAvailability' => 'LimitedAvailability',
							'http://schema.org/Discontinued' => 'Discontinued',
							'http://schema.org/PreOrder'   => 'PreOrder',
						],
						'desc'    => __( 'Select a availability type', 'wp-seo-structured-data-schema-pro' ),
					],
					'url'                  => [
						'title' => __( 'Course Url', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'url',
					],
					'validFrom'            => [
						'title' => __( 'Valid From', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'The date when the item becomes valid. Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'performerType'        => [
						'title'   => 'Performer Type',
						'type'    => 'select',
						'options' => [ 'Organization', 'Person' ],
					],
					'performerName'        => [
						'title' => __( 'Performer Name', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'review_section'       => [
						'title' => __( 'Review', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
						'desc'  => __( 'To add review schema for this type, complete fields below and enable, others live blank.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_active'        => [
						'type' => 'checkbox',
					],
					'review_author'        => [
						'title'    => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'review_author_sameAs' => [
						'title'    => __( 'Author Same As profile link', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'attr'     => 'placeholder="https://facebook.com/example&#10;https://twitter.com/example"',
						'required' => true,
						'desc'     => __( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_body'          => [
						'title'    => __( 'Review body', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'required' => true,
						'desc'     => __( 'The actual body of the review.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_datePublished' => [
						'title' => __( 'Date of Published', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_ratingValue'   => [
						'title' => __( 'Rating value', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_bestRating'    => [
						'title' => __( 'Best rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The highest value allowed in this rating system.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_worstRating'   => [
						'title' => __( 'Worst rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The lowest value allowed in this rating system. * Required if the rating system is not on a 5-point scale. If worstRating is omitted, 1 is assumed.', 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'person'               => [
				'title'  => esc_html__( 'Person', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'          => [
						'type' => 'checkbox',
					],
					'name'            => [
						'title'    => esc_html__( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'email'           => [
						'title'    => esc_html__( 'Email', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'email',
						'required' => true,
					],
					'jobTitle'        => [
						'title' => esc_html__( 'Job Title', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'image'           => [
						'title' => esc_html__( 'Image', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'image',
					],
					'birthPlace'      => [
						'title' => esc_html__( 'Birth Place', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'birthDate'       => [
						'title' => esc_html__( 'Birth Date', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => esc_html__( 'Like this: 2021-12-25', 'wp-seo-structured-data-schema-pro' ),
					],
					'height'          => [
						'title' => esc_html__( 'Height', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => esc_html__( 'eg. 72 inches', 'wp-seo-structured-data-schema-pro' ),
					],
					'gender'          => [
						'title'   => esc_html__( 'Gender', 'wp-seo-structured-data-schema-pro' ),
						'type'    => 'select',
						'options' => [
							'male'   => 'Male',
							'female' => 'Female',
						],
					],
					'memberOf'        => [
						'title' => esc_html__( 'Member Of', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => esc_html__( 'An Organization (or ProgramMembership) to which this Person or Organization belongs.', 'wp-seo-structured-data-schema-pro' ),
					],
					'nationality'     => [
						'title' => esc_html__( 'Nationality', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => esc_html__( 'Eg. Albanian', 'wp-seo-structured-data-schema-pro' ),
					],
					'telephone'       => [
						'title' => esc_html__( 'Telephone', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => esc_html__( 'Eg. (123) 456-6789', 'wp-seo-structured-data-schema-pro' ),
					],
					'url'             => [
						'title' => esc_html__( 'URL', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'url',
					],
					'sameAs'          => [
						'title'    => esc_html__( 'Same As', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'attr'     => 'placeholder="https://example.com/example&#10;https://example.com/example"',
						'required' => true,
						'desc'     => __( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry', 'wp-seo-structured-data-schema-pro' ),
					],
					'address_section' => [
						'title' => __( 'Address', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
					],
					'addressLocality' => [
						'title' => esc_html__( 'Address Locality', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'addressRegion'   => [
						'title' => esc_html__( 'Address Region', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'postalCode'      => [
						'title' => esc_html__( 'Postal Code', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'streetAddress'   => [
						'title' => esc_html__( 'Street Address', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
				],
			],
			'profilePage'          => [
				'title'  => esc_html__( 'Profile Page', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'        => [
						'type' => 'checkbox',
					],
					'profileFor'    => [
						'title'    => esc_html__( 'Profile For', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'select',
						'required' => true,
						'empty'    => 'Select one',
						'options'  => [
							'Person'       => 'Person',
							'Organization' => 'Organization',
						],
					],
					'name'          => [
						'title' => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'alternateName' => [
						'title'    => __( 'Alternate Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'gender'        => [
						'title' => __( 'Gender', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Use only "Profile For Person" Ex: Male', 'wp-seo-structured-data-schema-pro' ),
					],
					'image'         => [
						'type'  => 'image',
						'title' => esc_html__( 'Image', 'wp-seo-structured-data-schema-pro' ),
					],
					'description'   => [
						'title'       => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'textarea',
						'recommended' => true,
						'desc'        => __( 'Event description', 'wp-seo-structured-data-schema-pro' ),
					],
					'url'           => [
						'title' => esc_html__( 'URL', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'url',
					],
					'sameAs'        => [
						'title' => __( 'Profile Link( sameAs ) ', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'attr'  => 'placeholder="One item per line like bellow"',
						'desc'  => __( 'One item per line like bellow: <br> https://www.example.com/real-angelo<br>https://example.com/profile/therealangelohuff', 'wp-seo-structured-data-schema-pro' ),
					],
					'worksFor'      => [
						'type'      => 'group',
						'title'     => esc_html__( 'Works For', 'wp-seo-structured-data-schema-pro' ),
						'duplicate' => true,
						'fields'    => [
							'member_of'       => [
								'title' => __( 'Works For', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'heading',
							],
							'name'            => [
								'title' => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
							],
							'url'             => [
								'title' => esc_html__( 'URL', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'url',
							],
							'logo'            => [
								'type'  => 'image',
								'title' => esc_html__( 'Logo', 'wp-seo-structured-data-schema-pro' ),
							],
							'sameAs'          => [
								'title' => __( 'Comapny Link( sameAs ) ', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'textarea',
								'attr'  => 'placeholder="One item per line like bellow"',
								'desc'  => __( 'One item per line like bellow: <br> https://www.linkedin.com/company/example-company <br>https://example.com/profile/therealangelohuff', 'wp-seo-structured-data-schema-pro' ),
							],
							'department'      => [
								'title' => __( 'Department', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'heading',
							],
							'department_name' => [
								'title' => __( 'Department', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
							],
							'department_url'  => [
								'title' => esc_html__( 'Department URL', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'url',
							],
							'PostalAddress'   => [
								'title' => __( 'Postal Address', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'heading',
							],
							'streetAddress'   => [
								'title' => __( 'Street Address', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
							],
							'addressLocality' => [
								'title' => __( 'Address Locality', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
							],
							'region'          => [
								'title' => __( 'Region', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'Ex: CA ', 'wp-seo-structured-data-schema-pro' ),
							],
							'postalCode'      => [
								'title' => __( 'Postal Code', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
							],
							'addressCountry'  => [
								'title' => __( 'Country', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'Ex: US ', 'wp-seo-structured-data-schema-pro' ),
							],

						],
					],

					'memberOfList'  => [
						'type'      => 'group',
						'title'     => esc_html__( 'Member Of', 'wp-seo-structured-data-schema-pro' ),
						'duplicate' => true,
						'fields'    => [
							'member_of' => [
								'title' => __( 'Member Of', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'heading',
							],
							'type'      => [
								'title'   => __( 'Type', 'wp-seo-structured-data-schema-pro' ),
								'type'    => 'select',
								'empty'   => 'Select one',
								'options' => [
									'Organization'      => 'Organization',
									'ProgramMembership' => 'ProgramMembership',
								],
							],
							'name'      => [
								'title' => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
							],
						],
					],
					'dateCreated'   => [
						'title' => __( 'Created Date', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'dateModified'  => [
						'title'    => __( 'Modified date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'class'    => 'kcseo-date',
						'required' => true,
						'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],

				],
			],
			'event'                => [
				'title'  => __( 'Event', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'               => [
						'type' => 'checkbox',
					],
					'name'                 => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'The name of the event.', 'wp-seo-structured-data-schema-pro' ),
					],
					'locationName'         => [
						'title'    => __( 'Location name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'Event Location name', 'wp-seo-structured-data-schema-pro' ),
					],
					'locationAddress'      => [
						'title'    => __( 'Location address', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'The location of for example where the event is happening, an organization is located, or where an action takes place.', 'wp-seo-structured-data-schema-pro' ),
					],
					'startDate'            => [
						'title'    => __( 'Start date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'class'    => 'kcseo-date',
						'required' => true,
						'desc'     => __( 'Event start date, Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'endDate'              => [
						'title'       => __( 'End date', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'recommended' => true,
						'class'       => 'kcseo-date',
						'desc'        => __( 'Event end date, Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'description'          => [
						'title'       => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'textarea',
						'recommended' => true,
						'desc'        => __( 'Event description', 'wp-seo-structured-data-schema-pro' ),
					],
					'organizer'            => [
						'title'       => __( 'Organizer', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'recommended' => true,
						'desc'        => __( 'The Organizer.', 'wp-seo-structured-data-schema-pro' ),
					],
					'organizerUrl'         => [
						'title'       => __( 'Organizer URL', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'url',
						'recommended' => true,
						'desc'        => __( 'Organizer URL', 'wp-seo-structured-data-schema-pro' ),
					],
					'performerName'        => [
						'title'       => __( 'Performer Name', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'recommended' => true,
						'desc'        => __( "The performer's name.", 'wp-seo-structured-data-schema-pro' ),
					],
					'EventAttendanceMode'  => [
						'title'       => 'Event Attendance Mode',
						'type'        => 'select',
						'recommended' => true,
						'empty'       => 'Select one',
						'options'     => [
							'https://schema.org/OfflineEventAttendanceMode'  => 'Offline',
							'https://schema.org/OnlineEventAttendanceMode'  => 'Online',
							'https://schema.org/MixedEventAttendanceMode' => 'Mixed',
						],
					],
					'eventStatus'          => [
						'title'       => 'Event Status',
						'type'        => 'select',
						'recommended' => true,
						'empty'       => 'Select one',
						'options'     => [
							'https://schema.org/EventScheduled'  => 'EventScheduled',
							'https://schema.org/EventCancelled'  => 'EventCancelled',
							'https://schema.org/EventMovedOnline'  => 'EventMovedOnline',
							'https://schema.org/EventPostponed'  => 'EventPostponed',
							'https://schema.org/EventRescheduled'  => 'EventRescheduled',
						],
					],
					'image'                => [
						'title'       => __( 'Image or logo for the event or tour', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'image',
						'recommended' => true,
						'desc'        => __( 'URL of an image or logo for the event or tour. We recommend that images are 1920px wide (the minimum width is 720px).', 'wp-seo-structured-data-schema-pro' ),
					],
					'price'                => [
						'title'       => __( 'Price', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'number',
						'recommended' => true,
						'attr'        => 'step="any"',
						'desc'        => __( "This is highly recommended. The lowest available price, including service charges and fees, of this type of ticket. <span class='required'>Not required but (Recommended)</span>", 'wp-seo-structured-data-schema-pro' ),
					],
					'priceCurrency'        => [
						'title' => __( 'Price currency', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The 3-letter currency code. (USD)', 'wp-seo-structured-data-schema-pro' ),
					],
					'availability'         => [
						'title'       => 'Availability',
						'type'        => 'select',
						'recommended' => true,
						'empty'       => 'Select one',
						'options'     => [
							'http://schema.org/InStock'  => 'InStock',
							'http://schema.org/SoldOut'  => 'SoldOut',
							'http://schema.org/PreOrder' => 'PreOrder',
						],
					],
					'validFrom'            => [
						'title'       => __( 'Valid From', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'recommended' => true,
						'class'       => 'kcseo-date',
						'desc'        => __( sprintf( "The date and time when tickets go on sale (only required on date-restricted offers), in <a href='%s' target='_blank'>ISO-8601 format</a> Like this: 2024-01-05T08:00:00+08:00", 'https://en.wikipedia.org/wiki/ISO_8601' ), 'wp-seo-structured-data-schema-pro' ),
					],
					'url'                  => [
						'title'       => 'URL',
						'recommended' => true,
						'type'        => 'url',
						'placeholder' => 'URL',
						'desc'        => __( "A link to the event's details page. <span class='required'>Not required but (Recommended)</span>", 'wp-seo-structured-data-schema-pro' ),
					],
					'review_section'       => [
						'title' => __( 'Review', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
						'desc'  => __( 'To add review schema for this type, complete fields below and enable, others live blank.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_active'        => [
						'type' => 'checkbox',
					],
					'review_author'        => [
						'title'    => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'review_author_sameAs' => [
						'title'    => __( 'Author Same As profile link', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'attr'     => 'placeholder="https://facebook.com/example&#10;https://twitter.com/example"',
						'required' => true,
						'desc'     => __( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_body'          => [
						'title'    => __( 'Review body', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'required' => true,
						'desc'     => __( 'The actual body of the review.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_datePublished' => [
						'title' => __( 'Date of Published', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_ratingValue'   => [
						'title' => __( 'Rating value', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_bestRating'    => [
						'title' => __( 'Best rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The highest value allowed in this rating system.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_worstRating'   => [
						'title' => __( 'Worst rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The lowest value allowed in this rating system. * Required if the rating system is not on a 5-point scale. If worstRating is omitted, 1 is assumed.', 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'JobPosting'           => [
				'title'  => __( 'Job Posting', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'                 => [
						'type' => 'checkbox',
					],
					'title'                  => [
						'title' => __( 'Title', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'salaryAmount'           => [
						'title' => __( 'Base Salary', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 50.00, 'wp-seo-structured-data-schema-pro' ),
					],
					'currency'               => [
						'title' => __( 'Currency', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'USD', 'wp-seo-structured-data-schema-pro' ),
					],
					'salaryAt'               => [
						'title'   => 'Salary at',
						'type'    => 'select',
						'options' => [ 'MONTH', 'HOUR', 'WEEK', 'YEAR' ],
					],
					'datePosted'             => [
						'title' => __( 'Job posted date', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'validThrough'           => [
						'title' => __( 'Valid date through', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'description'            => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
					],
					'employmentType'         => [
						'title'   => 'Employment Type',
						'type'    => 'select',
						'options' => [
							'full-time',
							'part-time',
							'contract',
							'temporary',
							'seasonal',
							'internship',
						],
					],
					'workHours'              => [
						'title' => __( 'working Hours', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( '40 hours per week', 'wp-seo-structured-data-schema-pro' ),
					],
					'hiringOrganization'     => [
						'title' => __( 'Hiring Organization', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'addressLocality'        => [
						'title' => __( 'Job location address', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Kirkland', 'wp-seo-structured-data-schema-pro' ),
					],
					'addressRegion'          => [
						'title' => __( 'Job location region', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'WA', 'wp-seo-structured-data-schema-pro' ),
					],
					'postalCode'             => [
						'title' => __( 'Location Postal code', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'streetAddress'          => [
						'title' => __( 'Location street Address', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'jobBenefits'            => [
						'title' => __( 'Job Benefits', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Medical, Life, Dental', 'wp-seo-structured-data-schema-pro' ),
					],
					'educationRequirements'  => [
						'title' => __( 'Education Requirement', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'experienceRequirements' => [
						'title' => __( 'Experience Requirements', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'desc'  => __( 'Total month of experiance', 'wp-seo-structured-data-schema-pro' ),
					],
					'incentiveCompensation'  => [
						'title' => __( 'Incentive Compensation', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'industry'               => [
						'title' => __( 'Industry', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'occupationalCategory'   => [
						'title' => __( 'Occupational Category', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'qualifications'         => [
						'title' => __( 'Qualifications', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
					],
					'responsibilities'       => [
						'title' => __( 'Responsibilities', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
					],
					'skills'                 => [
						'title' => __( 'Skills', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
					],
				],
			],
			'localBusiness'        => [
				'title'  => __( 'Local Business', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'               => [
						'type' => 'checkbox',
					],
					'name'                 => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'description'          => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
					],
					'image'                => [
						'title'    => __( 'Business Logo', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'required' => true,
					],
					'priceRange'           => [
						'title' => __( 'Price Range (Recommended)', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The price range of the business, for example $$$.', 'wp-seo-structured-data-schema-pro' ),
					],
					'addressLocality'      => [
						'title' => __( 'Address locality', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'City (i.e Kansas city)', 'wp-seo-structured-data-schema-pro' ),
					],
					'addressRegion'        => [
						'title' => __( 'Address region', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'State (i.e. MO)', 'wp-seo-structured-data-schema-pro' ),
					],
					'postalCode'           => [
						'title' => __( 'Postal code', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'streetAddress'        => [
						'title' => __( 'Street address', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'telephone'            => [
						'title' => __( 'Telephone (Recommended)', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'review_section'       => [
						'title' => __( 'Review', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
						'desc'  => __( 'To add review schema for this type, complete fields below and enable, others live blank.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_active'        => [
						'type' => 'checkbox',
					],
					'review_author'        => [
						'title'    => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'review_author_sameAs' => [
						'title'    => __( 'Author Same As profile link', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'attr'     => 'placeholder="https://facebook.com/example&#10;https://twitter.com/example"',
						'required' => true,
						'desc'     => __( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_body'          => [
						'title'    => __( 'Review body', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'required' => true,
						'desc'     => __( 'The actual body of the review.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_datePublished' => [
						'title' => __( 'Date of Published', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_ratingValue'   => [
						'title' => __( 'Rating value', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_bestRating'    => [
						'title' => __( 'Best rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The highest value allowed in this rating system.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_worstRating'   => [
						'title' => __( 'Worst rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The lowest value allowed in this rating system. * Required if the rating system is not on a 5-point scale. If worstRating is omitted, 1 is assumed.', 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'software_application' => [
				'title'  => __( 'Software App', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'                   => [
						'type' => 'checkbox',
					],
					'name'                     => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'description'              => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
					],
					'image'                    => [
						'title'    => __( 'Business Logo', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'required' => true,
					],
					'price'                    => [
						'title' => __( 'Price', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The lowest available price, including service charges and fees, of this type of ticket.', 'wp-seo-structured-data-schema-pro' ),
					],
					'priceCurrency'            => [
						'title' => __( 'Price currency', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The 3-letter currency code.', 'wp-seo-structured-data-schema-pro' ),
					],
					'applicationCategory'      => [
						'title'    => __( 'Application Category', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'select',
						'required' => true,
						'options'  => self::getApplicationCategoryList(),
						'desc'     => __( 'The type of app (for example, BusinessApplication or GameApplication). The value must be a supported app type.', 'wp-seo-structured-data-schema-pro' ),
					],
					'operatingSystem'          => [
						'title' => __( 'Operating System', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The operating system(s) required to use the app (for example, Windows 7, OSX 10.6, Android 1.6)', 'wp-seo-structured-data-schema-pro' ),
					],
					'aggregate_rating_section' => [
						'title' => __( 'Aggregate Rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
					],
					'aggregate_ratingValue'    => [
						'title' => __( 'Rating value', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'aggregate_bestRating'     => [
						'title' => __( 'Best rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'aggregate_worstRating'    => [
						'title' => __( 'Worst rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'aggregate_ratingCount'    => [
						'title' => __( 'Rating Count', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_section'           => [
						'title' => __( 'Review', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
						'desc'  => __( 'To add review schema for this type, complete fields below and enable, others live blank.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_active'            => [
						'type' => 'checkbox',
					],
					'review_author'            => [
						'title'    => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'review_author_sameAs'     => [
						'title'    => __( 'Author Same As profile link', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'attr'     => 'placeholder="https://facebook.com/example&#10;https://twitter.com/example"',
						'required' => true,
						'desc'     => __( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_body'              => [
						'title'    => __( 'Review body', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'required' => true,
						'desc'     => __( 'The actual body of the review.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_datePublished'     => [
						'title' => __( 'Date of Published', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_ratingValue'       => [
						'title' => __( 'Rating value', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_bestRating'        => [
						'title' => __( 'Best rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The highest value allowed in this rating system.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_worstRating'       => [
						'title' => __( 'Worst rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The lowest value allowed in this rating system. * Required if the rating system is not on a 5-point scale. If worstRating is omitted, 1 is assumed.', 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'movie'                => [
				'title'  => __( 'Movie', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'                => [
						'type' => 'checkbox',
					],
					'name'                  => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'description'           => [
						'title'    => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'required' => true,
					],
					'duration'              => [
						'title' => __( 'Duration', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Runtime of the movie in ISO 8601 format (for example, "PT2H22M" (142 minutes)).', 'wp-seo-structured-data-schema-pro' ),
					],
					'dateCreated'           => [
						'title' => __( 'Created Date', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'image'                 => [
						'title'    => __( 'Image', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'required' => true,
					],
					'director'              => [
						'title' => __( 'Director', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'author'                => [
						'title' => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'attr'  => 'placeholder="One item per line like bellow"',
						'desc'  => __( 'Ted Elliott<br>Terry Rossio', 'wp-seo-structured-data-schema-pro' ),
					],
					'actor'                 => [
						'title' => __( 'Actor', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'attr'  => 'placeholder="One item per line like bellow"',
						'desc'  => __( 'Johnny Depp<br>Penelope Cruz<br>Ian McShane', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_section'        => [
						'title' => __( 'Review', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
						'desc'  => __( 'To add review schema for this type, complete fields below and enable, others live blank.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_active'         => [
						'type' => 'checkbox',
					],
					'review_author'         => [
						'title'    => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'review_author_sameAs'  => [
						'title'    => __( 'Author Same As profile link', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'attr'     => 'placeholder="https://facebook.com/example&#10;https://twitter.com/example"',
						'required' => true,
						'desc'     => __( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_body'           => [
						'title'    => __( 'Review body', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'required' => true,
						'desc'     => __( 'The actual body of the review.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_datePublished'  => [
						'title' => __( 'Date of Published', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_publisher'      => [
						'title'    => __( 'Publisher', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Publisher name or Organization name', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'review_publisherImage' => [
						'title' => __( 'Publisher Logo', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'image',
						'desc'  => __( 'Logos should have a wide aspect ratio, not a square icon.<br>Logos should be no wider than 600px, and no taller than 60px.<br>Always retain the original aspect ratio of the logo when resizing. Ideally, logos are exactly 60px tall with width <= 600px. If maintaining a height of 60px would cause the width to exceed 600px, downscale the logo to exactly 600px wide and reduce the height accordingly below 60px to maintain the original aspect ratio.<br>', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_sameAs'         => [
						'title'    => __( 'Review same as link', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'attr'     => 'placeholder="https://example.com/example&#10;https://example.com/example"',
						'required' => true,
						'desc'     => __( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_ratingValue'    => [
						'title' => __( 'Rating value', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_bestRating'     => [
						'title' => __( 'Best rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The highest value allowed in this rating system.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_worstRating'    => [
						'title' => __( 'Worst rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The lowest value allowed in this rating system. * Required if the rating system is not on a 5-point scale. If worstRating is omitted, 1 is assumed.', 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'music'                => [
				'title'  => __( 'Music', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'      => [
						'type' => 'checkbox',
					],
					'musicType'   => [
						'title'    => __( 'Music Type', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'select',
						'required' => true,
						'options'  => [
							'MusicGroup' => 'Music Artist',
							'MusicAlbum' => 'MusicAlbum',
						],
					],
					'name'        => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'description' => [
						'title'    => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'required' => true,
					],
					'image'       => [
						'title' => __( 'Image', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'image',
					],
					'sameAs'      => [
						'title' => __( 'Same as URL', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'url',
						'desc'  => __( 'URL of a page that unambiguously identifies the artist or album. Example: Wikipedia.', 'wp-seo-structured-data-schema-pro' ),
					],
					'url'         => [
						'title'    => __( 'URL', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'url',
						'required' => true,
						'desc'     => __( 'URL of the landing page of the artist or album on the partner site.', 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'news_article'         => [
				'title'  => __( 'News Article', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'           => [
						'type' => 'checkbox',
					],
					'headline'         => [
						'title'    => __( 'Headline', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Article title', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'mainEntityOfPage' => [
						'title'    => __( 'Page URL', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'url',
						'desc'     => __( 'The canonical URL of the article page', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'author'           => [
						'title'    => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Author display name', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'author_url'       => [
						'title'    => __( 'Author URL', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'url',
						'default'  => $author_url,
						'required' => true,
					],
					'image'            => [
						'title'    => __( 'Image', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'desc'     => __( 'The representative image of the article. Only a marked-up image that directly belongs to the article should be specified.<br> Images should be at least 696 pixels wide. <br>Images should be in .jpg, .png, or. gif format.', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'datePublished'    => [
						'title'    => __( 'Published date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'class'    => 'kcseo-date',
						'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'dateModified'     => [
						'title'    => __( 'Modified date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'class'    => 'kcseo-date',
						'required' => true,
						'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'publisher'        => [
						'title'    => __( 'Publisher', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'Publisher name or Organization name', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'publisherImage'   => [
						'title'    => __( 'Publisher Logo', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'desc'     => __( 'Logos should have a wide aspect ratio, not a square icon.<br>Logos should be no wider than 600px, and no taller than 60px.<br>Always retain the original aspect ratio of the logo when resizing. Ideally, logos are exactly 60px tall with width <= 600px. If maintaining a height of 60px would cause the width to exceed 600px, downscale the logo to exactly 600px wide and reduce the height accordingly below 60px to maintain the original aspect ratio.<br>', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'description'      => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Short description, New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],
					'articleBody'      => [
						'title' => __( 'Article body', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Article body content, New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],
					'video'            => [
						'title'  => __( 'Video', 'wp-seo-structured-data-schema-pro' ),
						'type'   => 'group',
						'fields' => [
							'video_heading' => [
								'type'  => 'heading',
								'title' => __( 'Video', 'wp-seo-structured-data-schema-pro' ),
							],
							'name'          => [
								'type'  => 'text',
								'title' => esc_html__( 'Name', 'wp-seo-structured-data-schema-pro' ),
							],
							'description'   => [
								'type'  => 'textarea',
								'title' => esc_html__( 'Description', 'wp-seo-structured-data-schema-pro' ),
							],
							'thumbnailUrl'  => [
								'type'  => 'image',
								'title' => esc_html__( 'Image', 'wp-seo-structured-data-schema-pro' ),
							],
							'contentUrl'    => [
								'type'  => 'url',
								'title' => esc_html__( 'Content URL', 'wp-seo-structured-data-schema-pro' ),
							],
							'embedUrl'      => [
								'type'  => 'url',
								'title' => esc_html__( 'Embed URL', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'A URL pointing to the actual video media file. This file should be in .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv, or other video file format.', 'wp-seo-structured-data-schema-pro' ),
							],
							'uploadDate'    => [
								'type'     => 'text',
								'title'    => esc_html__( 'Upload date', 'wp-seo-structured-data-schema-pro' ),
								'class'    => 'rtrs-date',
								'required' => true,
								'desc'     => esc_html__( 'Like this: 2021-08-25 14:20:00', 'wp-seo-structured-data-schema-pro' ),
							],
							'duration'      => [
								'type'  => 'text',
								'title' => esc_html__( 'Duration', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'Runtime of the movie in ISO 8601 format (for example, "PT2H22M" (142 minutes)).', 'wp-seo-structured-data-schema-pro' ),
							],
						],
					],
					'audio'            => [
						'title'  => __( 'Audio', 'wp-seo-structured-data-schema-pro' ),
						'type'   => 'group',
						'fields' => [
							'audio_heading'  => [
								'type'  => 'heading',
								'title' => __( 'Audio', 'wp-seo-structured-data-schema-pro' ),
							],
							'name'           => [
								'type'  => 'text',
								'title' => esc_html__( 'Name', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'The title of the audio', 'wp-seo-structured-data-schema-pro' ),
							],
							'description'    => [
								'type'  => 'textarea',
								'title' => esc_html__( 'Description', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'The short description of the audio', 'wp-seo-structured-data-schema-pro' ),
							],
							'duration'       => [
								'type'  => 'text',
								'title' => esc_html__( 'Duration', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( 'The duration of the audio in ISO 8601 format.(PT1M33S)', 'wp-seo-structured-data-schema-pro' ),
							],
							'contentUrl'     => [
								'type'        => 'url',
								'title'       => esc_html__( 'Content URL', 'wp-seo-structured-data-schema-pro' ),
								'placeholder' => esc_html__( 'URL', 'wp-seo-structured-data-schema-pro' ),
								'desc'        => esc_html__( 'A URL pointing to the actual audio media file. This file should be in .mp3, .wav, .mpc or other audio file format.', 'wp-seo-structured-data-schema-pro' ),
							],
							'encodingFormat' => [
								'type'  => 'text',
								'title' => esc_html__( 'Encoding Format', 'wp-seo-structured-data-schema-pro' ),
								'desc'  => esc_html__( "The encoding format of audio like: 'audio/mpeg'", 'wp-seo-structured-data-schema-pro' ),
							],
						],
					],
				],
			],
			'product'              => [
				'title'  => __( ( class_exists( 'woocommerce' ) ) ? 'Product (Woocommerce)' : 'Product', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'               => [
						'type' => 'checkbox',
					],
					'name'                 => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'image'                => [
						'title' => __( 'Image', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'image',
					],
					'description'          => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Product description. ', 'wp-seo-structured-data-schema-pro' ),
					],
					'identifier_section'   => [
						'title' => __( 'Product Identifier', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
						'desc'  => __( 'Add Product unique Identifier.', 'wp-seo-structured-data-schema-pro' ),
					],
					'sku'                  => [
						'title'       => __( 'SKU', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'recommended' => true,
					],
					'brand'                => [
						'title'    => __( 'BRAND', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'The brand of the product (Used globally).', 'wp-seo-structured-data-schema-pro' ),
					],
					'identifier_type'      => [
						'title'    => __( 'Identifier Type', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'select',
						'required' => true,
						'options'  => [
							'mpn'    => 'MPN',
							'isbn'   => 'ISBN',
							'gtin8'  => 'GTIN-8 (UPC, JAN)',
							'gtin12' => 'GTIN-12 (UPC)',
							'gtin13' => 'GTIN-13 (EAN,JAN)',
						],
						'desc'     => __(
							'<strong>MPN</strong><br>
                                       &#8594; MPN(Manufacturer Part Number) Used globally, Alphanumeric digits (various lengths)<br>
                                       <strong>GTIN</strong><br>
                                       &#8594; UPC(Universal Product Code) Used in primarily North America. 12 numeric digits. eg. 892685001003.<br>
                                       &#8594; EAN(European Article Number) Used primarily outside of North America. Typically 13 numeric digits (can occasionally be either eight or 14 numeric digits). eg. 4011200296908<br>
                                       &#8594; ISBN(International Standard Book Number) Used globally, ISBN-13 (recommended), 13 numeric digits 978-0747595823<br>
                                       &#8594; JAN(Japanese Article Number) Used only in Japan, 8 or 13 numeric digits.',
							'wp-seo-structured-data-schema-pro'
						),
					],
					'identifier'           => [
						'title'    => __( 'Identifier', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'Enter product unique identifier', 'wp-seo-structured-data-schema-pro' ),
					],
					'rating_section'       => [
						'title' => __( 'Product Review & Rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
					],
					'reviewRatingValue'    => [
						'title'       => __( 'Review rating value', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'number',
						'recommended' => true,
						'attr'        => 'step="any"',
						'desc'        => __( 'Rating value. (1 , 2.5, 3, 5 etc)', 'wp-seo-structured-data-schema-pro' ),
					],
					'reviewBestRating'     => [
						'title'       => __( 'Review Best rating', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'number',
						'recommended' => true,
						'attr'        => 'step="any"',
					],
					'reviewWorstRating'    => [
						'title'       => __( 'Review Worst rating', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'number',
						'recommended' => true,
						'attr'        => 'step="any"',
					],
					'reviewAuthor'         => [
						'title' => __( 'Review author', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'ratingValue'          => [
						'title'       => __( 'Aggregate Rating value', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'number',
						'recommended' => true,
						'attr'        => 'step="any"',
						'desc'        => __( 'Rating value. (1 , 2.5, 3, 5 etc)', 'wp-seo-structured-data-schema-pro' ),
					],
					'reviewCount'          => [
						'title' => __( 'Aggregate Total review count', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( "Review Count. <span class='required'>This is required if (Rating value) is given</span>", 'wp-seo-structured-data-schema-pro' ),
					],
					'pricing_section'      => [
						'title' => __( 'Product Pricing', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
					],
					'priceCurrency'        => [
						'title' => __( 'Price currency', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The 3-letter currency code.', 'wp-seo-structured-data-schema-pro' ),
					],
					'price'                => [
						'title' => __( 'Price', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The lowest available price, including service charges and fees, of this type of ticket.', 'wp-seo-structured-data-schema-pro' ),
					],
					'priceValidUntil'      => [
						'title'       => __( 'PriceValidUntil', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'recommended' => true,
						'class'       => 'kcseo-date',
						'desc'        => __( 'The date (in ISO 8601 date format) after which the price will no longer be available. Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'availability'         => [
						'title'   => 'Availability',
						'type'    => 'select',
						'empty'   => 'Select one',
						'options' => [
							'http://schema.org/InStock'    => 'InStock',
							'http://schema.org/InStoreOnly' => 'InStoreOnly',
							'http://schema.org/OutOfStock' => 'OutOfStock',
							'http://schema.org/SoldOut'    => 'SoldOut',
							'http://schema.org/OnlineOnly' => 'OnlineOnly',
							'http://schema.org/LimitedAvailability' => 'LimitedAvailability',
							'http://schema.org/Discontinued' => 'Discontinued',
							'http://schema.org/PreOrder'   => 'PreOrder',
						],
						'desc'    => __( 'Select a availability type', 'wp-seo-structured-data-schema-pro' ),
					],
					'itemCondition'        => [
						'title'   => 'Product condition',
						'type'    => 'select',
						'empty'   => 'Select one',
						'options' => [
							'http://schema.org/NewCondition'         => 'NewCondition',
							'http://schema.org/UsedCondition'        => 'UsedCondition',
							'http://schema.org/DamagedCondition'     => 'DamagedCondition',
							'http://schema.org/RefurbishedCondition' => 'RefurbishedCondition',
						],
						'desc'    => __( 'Select a condition', 'wp-seo-structured-data-schema-pro' ),
					],
					'url'                  => [
						'title' => __( 'Product URL', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'url',
						'desc'  => __( "A URL to the product web page (that includes the Offer). (Don't use offerURL for markup that appears on the product page itself.)", 'wp-seo-structured-data-schema-pro' ),
					],

					'shipping_details'     => [
						'type'  => 'heading',
						'title' => esc_html__( 'Shipping Details', 'wp-seo-structured-data-schema-pro' ),
					],
					'shippingRate'         => [
						'type'  => 'number',
						'title' => esc_html__( 'Shipping Rate ( Price )', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'Shipping Cost.', 'wp-seo-structured-data-schema-pro' ),

					],
					'shippingDestination'  => [
						'type'  => 'text',
						'attr'  => 'placeholder="US"',
						'title' => esc_html__( 'Shipping Destination', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'The two-letter country code, in ISO 3166-1 alpha-2 format.', 'wp-seo-structured-data-schema-pro' ),
					],
					'addressRegion'        => [
						'type'  => 'text',
						'attr'  => 'placeholder="NY", "AL", "AK"',
						'title' => esc_html__( 'Address Region', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'If you include this property, the region must be a 2- or 3-digit ISO 3166-2 subdivision code, without country prefix. Currently, Google Search only supports the US, Australia, and Japan. Examples: "NY" (for US, state of New York), "NSW" (for Australia, state of New South Wales), or "03" (for Japan, Iwate prefecture).Example: "NY", "AL", "AK".', 'wp-seo-structured-data-schema-pro' ),
					],
					'handlingTime'         => [
						'type'  => 'heading',
						'title' => esc_html__( 'Handling Time', 'wp-seo-structured-data-schema-pro' ),
					],
					'handlingTimeMinimum'  => [
						'type'  => 'number',
						'title' => esc_html__( 'Handling Time Minimum ( Days )', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'Minimum days for handling time.', 'wp-seo-structured-data-schema-pro' ),
					],
					'handlingTimeMaximum'  => [
						'type'  => 'number',
						'title' => esc_html__( 'Handling Time Maximum (Days)', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'Maximum days for handling time.', 'wp-seo-structured-data-schema-pro' ),
					],
					'transitTimeMinimum'   => [
						'type'  => 'number',
						'title' => esc_html__( 'Transit Time Minimum ( Days )', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'Minimum days for Transit Time.', 'wp-seo-structured-data-schema-pro' ),
					],
					'transitTimeMaximum'   => [
						'type'  => 'number',
						'title' => esc_html__( 'Transit Time Maximum ( Days )', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'Maximum days for Transit Time.', 'wp-seo-structured-data-schema-pro' ),
					],

					'MerchantReturnPolicy' => [
						'type'  => 'heading',
						'title' => esc_html__( 'Merchant Return Policy', 'wp-seo-structured-data-schema-pro' ),
					],

					'applicableCountry'    => [
						'type'  => 'text',
						'attr'  => 'placeholder="US"',
						'title' => esc_html__( 'Applicable Country', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'The two-letter country code, in ISO 3166-1 alpha-2 format.', 'wp-seo-structured-data-schema-pro' ),
					],

					'merchantReturnDays'   => [
						'type'  => 'number',
						'title' => esc_html__( 'Merchant Return Days', 'wp-seo-structured-data-schema-pro' ),
					],
					/*
					'returnPolicyCategory' => [
						'type'  => 'url',
						'title' => esc_html__( 'Return Policy Category URL', 'wp-seo-structured-data-schema-pro' ),
					],
					'returnMethod'         => [
						'type'  => 'url',
						'title' => esc_html__( 'Return Method', 'wp-seo-structured-data-schema-pro' ),
					],
					'returnFees'           => [
						'type'  => 'url',
						'title' => esc_html__( 'Return Fees', 'wp-seo-structured-data-schema-pro' ),
					],
					*/
				],
			],
			'recipe'               => [
				'title'  => __( 'Recipe', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'               => [
						'type' => 'checkbox',
					],
					'name'                 => [
						'title' => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'author'               => [
						'title' => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'datePublished'        => [
						'title' => __( 'Published Date', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'image'                => [
						'title'    => __( 'Image', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'required' => true,
					],
					'description'          => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
					],
					'keywords'             => [
						'title'       => __( 'Recipe keywords', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'recommended' => true,
						'desc'        => __( 'Pizza, Nice, Testy', 'wp-seo-structured-data-schema-pro' ),
					],
					'recipeCategory'       => [
						'title'       => __( 'Recipe Category', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'recommended' => true,
						'desc'        => __( 'example, appetizer, entree, etc.', 'wp-seo-structured-data-schema-pro' ),
					],
					'recipeCuisine'        => [
						'title'       => __( 'Recipe Cuisine', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'recommended' => true,
						'desc'        => __( 'example, French or Ethiopian', 'wp-seo-structured-data-schema-pro' ),
					],
					/*
					 'video'                => array(
						'title'       => __('Recipe video url', "wp-seo-structured-data-schema-pro"),
						'type'        => 'url',
						'recommended' => true,
					), */
					'prepTime'             => [
						'title' => __( 'Prepare Time', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'PT15M', 'wp-seo-structured-data-schema-pro' ),
					],
					'cookTime'             => [
						'title' => __( 'Cook Time', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'PT1H', 'wp-seo-structured-data-schema-pro' ),
					],
					/*
					'recipeInstructions' => array(
						'title' => __('Recipe Instructions', "wp-seo-structured-data-schema-pro"),
						'type'  => 'textarea',
					),
					*/
					'recipe_instructions'  => [
						'type'      => 'group',
						'duplicate' => true,
						'title'     => esc_html__( 'Recipe Instructions', 'wp-seo-structured-data-schema-pro' ),
						'fields'    => [
							'heading' => [
								'type'  => 'heading',
								'title' => esc_html__( 'Recipe Instructions', 'wp-seo-structured-data-schema-pro' ),
							],
							'name'    => [
								'type'     => 'text',
								'required' => true,
								'title'    => esc_html__( 'Name', 'wp-seo-structured-data-schema-pro' ),
							],
							'text'    => [
								'type'  => 'textarea',
								'title' => esc_html__( 'Description', 'wp-seo-structured-data-schema-pro' ),
							],
							'image'   => [
								'type'  => 'image',
								'title' => esc_html__( 'Image', 'wp-seo-structured-data-schema-pro' ),
							],
							'url'     => [
								'type'  => 'url',
								'title' => esc_html__( 'URL', 'wp-seo-structured-data-schema-pro' ),
							],
						],
					],
					'recipeIngredient'     => [
						'title' => __( 'Recipe Ingredient', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'attr'  => 'placeholder="One item per line like bellow"',
						'desc'  => __( '3 or 4 ripe bananas, smashed<br>1 egg<br> 3/4 cup of sugar', 'wp-seo-structured-data-schema-pro' ),
					],
					'calories'             => [
						'title' => __( 'Nutrition: calories', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( '240 calories', 'wp-seo-structured-data-schema-pro' ),
					],
					'fatContent'           => [
						'title' => __( 'Nutrition: Fat Content', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( '9 grams fat', 'wp-seo-structured-data-schema-pro' ),
					],
					'video_info'           => [
						'type'        => 'group',
						'recommended' => true,
						'title'       => esc_html__( 'Video Info', 'wp-seo-structured-data-schema-pro' ),
						'fields'      => [
							'heading'      => [
								'type'  => 'heading',
								'title' => esc_html__( 'Video Info', 'wp-seo-structured-data-schema-pro' ),
							],
							'name'         => [
								'type'     => 'text',
								'title'    => esc_html__( 'Name', 'wp-seo-structured-data-schema-pro' ),
								'required' => true,
							],
							'description'  => [
								'type'     => 'textarea',
								'title'    => esc_html__( 'Description', 'wp-seo-structured-data-schema-pro' ),
								'required' => true,
							],
							'thumbnailUrl' => [
								'type'     => 'image',
								'title'    => esc_html__( 'Image', 'wp-seo-structured-data-schema-pro' ),
								'required' => true,
							],
							'contentUrl'   => [
								'type'     => 'url',
								'title'    => esc_html__( 'Content URL', 'wp-seo-structured-data-schema-pro' ),
								'required' => true,
							],
							'embedUrl'     => [
								'type'     => 'url',
								'title'    => esc_html__( 'Embed URL', 'wp-seo-structured-data-schema-pro' ),
								'required' => true,
							],
							'uploadDate'   => [
								'type'     => 'text',
								'title'    => esc_html__( 'Upload date', 'wp-seo-structured-data-schema-pro' ),
								'class'    => 'rtrs-date',
								'desc'     => esc_html__( 'Like this: 2021-08-25 14:20:00', 'wp-seo-structured-data-schema-pro' ),
								'required' => true,
							],
							'duration'     => [
								'type'     => 'text',
								'title'    => esc_html__( 'Duration', 'wp-seo-structured-data-schema-pro' ),
								'desc'     => esc_html__( 'Runtime of the movie in ISO 8601 format (for example, "PT2H22M" (142 minutes)).', 'wp-seo-structured-data-schema-pro' ),
								'required' => true,
							],
						],
					],
					'userInteractionCount' => [
						'title' => __( 'User Interaction Count', 'wp-seo-structured-data-schema-pro' ),
						'attr'  => 'step="any"',
						'type'  => 'number',
					],
					'ratingValue'          => [
						'title' => __( 'Rating value', 'wp-seo-structured-data-schema-pro' ),
						'attr'  => 'step="any"',
						'type'  => 'number',
					],
					'reviewCount'          => [
						'title' => __( 'Review Count', 'wp-seo-structured-data-schema-pro' ),
						'attr'  => 'step="any"',
						'type'  => 'number',
					],
					'bestRating'           => [
						'title' => __( 'Best rating', 'wp-seo-structured-data-schema-pro' ),
						'attr'  => 'step="any"',
						'type'  => 'number',
					],
					'worstRating'          => [
						'title' => __( 'Worst rating', 'wp-seo-structured-data-schema-pro' ),
						'attr'  => 'step="any"',
						'type'  => 'number',
					],
					'recipeYield'          => [
						'title' => __( 'Recipe Yield', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
					],
					'suitableForDiet'      => [
						'title' => __( 'Suitable ForDiet', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'http://schema.org/LowFatDiet', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_section'       => [
						'title' => __( 'Review', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
						'desc'  => __( 'To add review schema for this type, complete fields below and enable, others live blank.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_active'        => [
						'type' => 'checkbox',
					],
					'review_author'        => [
						'title'    => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'review_author_sameAs' => [
						'title'    => __( 'Author Same As profile link', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'attr'     => 'placeholder="https://facebook.com/example&#10;https://twitter.com/example"',
						'required' => true,
						'desc'     => __( 'A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_body'          => [
						'title'    => __( 'Review body', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'required' => true,
						'desc'     => __( 'The actual body of the review.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_datePublished' => [
						'title' => __( 'Date of Published', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_ratingValue'   => [
						'title' => __( 'Rating value', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_bestRating'    => [
						'title' => __( 'Best rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The highest value allowed in this rating system.', 'wp-seo-structured-data-schema-pro' ),
					],
					'review_worstRating'   => [
						'title' => __( 'Worst rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The lowest value allowed in this rating system. * Required if the rating system is not on a 5-point scale. If worstRating is omitted, 1 is assumed.', 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'restaurant'           => [
				'title'  => __( 'Restaurant', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'                      => [
						'type' => 'checkbox',
					],
					'name'                        => [
						'title'    => __( 'Name of the Restaurant', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'description'                 => [
						'title' => __( 'Description of the Restaurant', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
					],
					'openingHours'                => [
						'title' => __( 'Opening Hours', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Mo,Tu,We,Th,Fr,Sa,Su 11:30-23:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'telephone'                   => [
						'title' => __( 'Telephone', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( '+155501003333', 'wp-seo-structured-data-schema-pro' ),
					],
					'address'                     => [
						'title' => __( 'Address', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
					],
					'priceRange'                  => [
						'title' => __( 'Price Range', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The price range of the business, for example $$$.', 'wp-seo-structured-data-schema-pro' ),
					],
					'servesCuisine'               => [
						'title' => __( 'Serves Cuisine', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'attr'  => 'placeholder="One item per line like bellow"',
					],
					'image'                       => [
						'title'    => __( 'Image', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'image',
						'required' => true,
					],
					'menu_section'                => [
						'title' => __( 'Menu Section', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
						'desc'  => __( 'Add your menu here.', 'wp-seo-structured-data-schema-pro' ),
					],
					'menuName'                    => [
						'title' => __( 'Menu Name', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'menuDescription'             => [
						'title' => __( 'Menu description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
					],
					'menuImage'                   => [
						'title' => __( 'Menu Image', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'image',
					],
					'menuOfferAvailabilityStarts' => [
						'title' => __( 'Menu Offer availabilityStarts', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
					],
					'menuOfferAvailabilityEnds'   => [
						'title' => __( 'Menu Offer availabilityEnds', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
					],
					'menu_items'                  => [
						'title'     => __( 'Menu Item', 'wp-seo-structured-data-schema-pro' ),
						'type'      => 'group',
						'duplicate' => true,
						'fields'    => [
							'menu_item_section'            => [
								'title' => __( 'Menu Item', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'heading',
								'desc'  => __( 'Add your menu item here.', 'wp-seo-structured-data-schema-pro' ),
							],
							'name'                         => [
								'title' => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
							],
							'description'                  => [
								'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'textarea',
							],
							'pricing_section_heading'      => [
								'title' => __( 'Offer', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'heading',
							],
							'offers_price'                 => [
								'title' => __( 'Price', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'number',
							],
							'offers_priceCurrency'         => [
								'title' => __( 'Price Currency', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
							],
							'nutrition_section_heading'    => [
								'title' => __( 'Nutrition', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'heading',
							],
							'nutrition_calories'           => [
								'title' => __( 'Calories', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'The number of calories.', 'wp-seo-structured-data-schema-pro' ),
							],
							'nutrition_carbohydrateContent' => [
								'title' => __( 'Carbohydrates', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'The number of grams of carbohydrates.', 'wp-seo-structured-data-schema-pro' ),
							],
							'nutrition_cholesterolContent' => [
								'title' => __( 'Cholesterol', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'The number of milligrams of cholesterol.', 'wp-seo-structured-data-schema-pro' ),
							],
							'nutrition_fatContent'         => [
								'title' => __( 'Fat', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'The number of grams of fat.', 'wp-seo-structured-data-schema-pro' ),
							],
							'nutrition_fiberContent'       => [
								'title' => __( 'Fiber', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'The number of grams of fiber.', 'wp-seo-structured-data-schema-pro' ),
							],
							'nutrition_proteinContent'     => [
								'title' => __( 'Protein', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'The number of grams of protein.', 'wp-seo-structured-data-schema-pro' ),
							],
							'nutrition_saturatedFatContent' => [
								'title' => __( 'Saturated Fat', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'The number of grams of saturated fat.', 'wp-seo-structured-data-schema-pro' ),
							],
							'nutrition_servingSize'        => [
								'title' => __( 'Serving size', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'The serving size, in terms of the number of volume or mass.', 'wp-seo-structured-data-schema-pro' ),
							],
							'nutrition_sodiumContent'      => [
								'title' => __( 'Sodium', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'The number of milligrams of sodium.', 'wp-seo-structured-data-schema-pro' ),
							],
							'nutrition_sugarContent'       => [
								'title' => __( 'Sugar', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'The number of grams of sugar.', 'wp-seo-structured-data-schema-pro' ),
							],
							'nutrition_transFatContent'    => [
								'title' => __( 'Trans fat', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'The number of grams of trans fat.', 'wp-seo-structured-data-schema-pro' ),
							],
							'nutrition_unsaturatedFatContent' => [
								'title' => __( 'Cholesterol', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'The number of grams of unsaturated fat.', 'wp-seo-structured-data-schema-pro' ),
							],
						],
					],
				],
			],
			'review'               => [
				'title'  => __( 'Review', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'                => [
						'type' => 'checkbox',
					],
					'review_notice_heading' => [
						'title' => sprintf( '<span style="display:block;text-align:center;color: red">%s</span>', __( 'Notice</span>', 'wp-seo-structured-data-schema-pro' ) ),
						'type'  => 'heading',
						'desc'  => self::getReviewNotice(),
					],
					'itemName'              => [
						'title'    => __( 'Name of the reviewed item', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'The item that is being reviewed.', 'wp-seo-structured-data-schema-pro' ),
					],
					'reviewBody'            => [
						'title'    => __( 'Review body', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'required' => true,
						'desc'     => __( 'The actual body of the review.', 'wp-seo-structured-data-schema-pro' ),
					],
					'name'                  => [
						'title'    => __( 'Review name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'A particular name for the review.', 'wp-seo-structured-data-schema-pro' ),
					],
					'author'                => [
						'title'    => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'author'   => 'Author name',
						'desc'     => __( 'The author of the review. The reviewer’s name needs to be a valid name.', 'wp-seo-structured-data-schema-pro' ),
					],
					'author_url'            => [
						'title'    => __( 'Author URL', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'url',
						'default'  => $author_url,
						'required' => true,
					],
					'datePublished'         => [
						'title' => __( 'Date of Published', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'ratingValue'           => [
						'title' => __( 'Rating value', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'bestRating'            => [
						'title' => __( 'Best rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The highest value allowed in this rating system.', 'wp-seo-structured-data-schema-pro' ),
					],
					'worstRating'           => [
						'title' => __( 'Worst rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The lowest value allowed in this rating system. * Required if the rating system is not on a 5-point scale. If worstRating is omitted, 1 is assumed.', 'wp-seo-structured-data-schema-pro' ),
					],
					'publisher'             => [
						'title' => __( 'Name of the organization', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The publisher of the review.', 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'service'              => [
				'title'  => __( 'Service', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'           => [
						'type' => 'checkbox',
					],
					'name'             => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'The name of the Service.', 'wp-seo-structured-data-schema-pro' ),
					],
					'serviceType'      => [
						'title'    => __( 'Service type', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( "The type of service being offered, e.g. veterans' benefits, emergency relief, etc.", 'wp-seo-structured-data-schema-pro' ),
					],
					'additionalType'   => [
						'title'       => 'Additional type(URL)',
						'type'        => 'url',
						'placeholder' => 'URL',
						'desc'        => __( 'An additional type for the service, typically used for adding more specific types from external vocabularies in microdata syntax.', 'wp-seo-structured-data-schema-pro' ),
					],
					'award'            => [
						'title' => __( 'Award', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'An award won by or for this service.', 'wp-seo-structured-data-schema-pro' ),
					],
					'category'         => [
						'title' => __( 'Category', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'A category for the service.', 'wp-seo-structured-data-schema-pro' ),
					],
					'providerMobility' => [
						'title' => __( 'Provider mobility', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( "Indicates the mobility of a provided service (e.g. 'static', 'dynamic').", 'wp-seo-structured-data-schema-pro' ),
					],
					'description'      => [
						'title'   => 'Description',
						'type'    => 'textarea',
						'require' => true,
						'desc'    => __( 'A short description of the service. New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],
					'image'            => [
						'title'   => 'Image URL',
						'type'    => 'url',
						'require' => false,
						'desc'    => __( 'An image of the service. This should be a URL.', 'wp-seo-structured-data-schema-pro' ),
					],
					'mainEntityOfPage' => [
						'title'   => 'Main entity of page URL',
						'type'    => 'url',
						'require' => false,
						'desc'    => __( 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described.', 'wp-seo-structured-data-schema-pro' ),
					],
					'sameAs'           => [
						'title'       => 'Same as URL',
						'type'        => 'url',
						'placeholder' => 'URL',
						'desc'        => __( "URL of a reference Web page that unambiguously indicates the service's identity. E.g. the URL of the service's Wikipedia page, Freebase page, or official website.", 'wp-seo-structured-data-schema-pro' ),
					],
					'url'              => [
						'title'       => 'Url of the service',
						'type'        => 'url',
						'placeholder' => 'URL',
						'desc'        => __( 'URL of the service.', 'wp-seo-structured-data-schema-pro' ),
					],
					'alternateName'    => [
						'title' => __( 'Alternate name', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'An alias for the service.', 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'about'                => [
				'title'  => __( 'About', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'      => [
						'type' => 'checkbox',
					],
					'name'        => [
						'type'     => 'text',
						'title'    => esc_html__( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'description' => [
						'type'  => 'textarea',
						'title' => esc_html__( 'Description', 'wp-seo-structured-data-schema-pro' ),
					],
					'image'       => [
						'type'  => 'image',
						'title' => esc_html__( 'Image', 'wp-seo-structured-data-schema-pro' ),
					],
					'url'         => [
						'type'  => 'url',
						'title' => esc_html__( 'URL', 'wp-seo-structured-data-schema-pro' ),
					],
					'sameAs'      => [
						'type'        => 'textarea',
						'title'       => esc_html__( 'Author Same As profile link', 'wp-seo-structured-data-schema-pro' ),
						'placeholder' => 'https://facebook.com/example&#10;https://twitter.com/example',
						'desc'        => wp_kses( __( "A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry", 'wp-seo-structured-data-schema-pro' ), [ 'br' => [] ] ),
					],
				],
			],
			'contact'              => [
				'title'  => __( 'Contact', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'      => [
						'type' => 'checkbox',
					],
					'name'        => [
						'type'     => 'text',
						'title'    => esc_html__( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'description' => [
						'type'  => 'textarea',
						'title' => esc_html__( 'Description', 'wp-seo-structured-data-schema-pro' ),
					],
					'image'       => [
						'type'  => 'image',
						'title' => esc_html__( 'Image', 'wp-seo-structured-data-schema-pro' ),
					],
					'url'         => [
						'type'  => 'url',
						'title' => esc_html__( 'URL', 'wp-seo-structured-data-schema-pro' ),
					],
					'video'       => [
						'type'        => 'url',
						'title'       => esc_html__( 'Video URL', 'wp-seo-structured-data-schema-pro' ),
						'placeholder' => esc_html__( 'URL', 'wp-seo-structured-data-schema-pro' ),
						'desc'        => esc_html__( 'A URL pointing to the actual video media file. This file should be in .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv, or other video file format.', 'wp-seo-structured-data-schema-pro' ),
					],
					'sameAs'      => [
						'type'        => 'textarea',
						'title'       => esc_html__( 'Author Same As profile link', 'wp-seo-structured-data-schema-pro' ),
						'placeholder' => 'https://facebook.com/example&#10;https://twitter.com/example',
						'desc'        => wp_kses( __( "A reference page that unambiguously indicates the item\'s identity; for example, the URL of the item\'s Wikipedia page, Freebase page, or official website.<br> Enter new line for every entry", 'wp-seo-structured-data-schema-pro' ), [ 'br' => [] ] ),
					],
				],
			],
			'TVEpisode'            => [
				'title'  => __( 'TVEpisode', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'        => [
						'type' => 'checkbox',
					],
					'name'          => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'author'        => [
						'title' => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'actor'         => [
						'title' => __( 'Actor', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'attr'  => 'placeholder="One item per line like bellow"',
						'desc'  => __( 'Justin Chambers<br>Jessica Capshaw', 'wp-seo-structured-data-schema-pro' ),
					],
					'episodeNumber' => [
						'title'    => __( 'Episode Number', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'number',
						'attr'     => 'step="any"',
						'required' => true,
						'desc'     => __( 'Position of the episode within an ordered group of episodes for a given season.', 'wp-seo-structured-data-schema-pro' ),
					],
					'seasonNumber'  => [
						'title'    => __( 'Season Number', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'number',
						'attr'     => 'step="any"',
						'required' => true,
						'desc'     => __( 'Position of the season within an ordered group of seasons.', 'wp-seo-structured-data-schema-pro' ),
					],
					'seriesName'    => [
						'title'    => __( 'Series name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'Name of the TV series.', 'wp-seo-structured-data-schema-pro' ),
					],
					'seriesURL'     => [
						'title' => __( 'Series URL', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'url',
						'desc'  => __( 'URL to a reference web page that unambiguously identifies the series. Example: IMDB, Wikipedia.', 'wp-seo-structured-data-schema-pro' ),
					],
					'startDate'     => [
						'title' => __( 'Released Event (StartDate)', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'sameAs'        => [
						'title' => __( 'Episode URL', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'url',
						'desc'  => __( 'URL to a reference web page that unambiguously identifies the work. Example: IMDB, Wikipedia.', 'wp-seo-structured-data-schema-pro' ),
					],
					'url'           => [
						'title' => __( 'URL', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'url',
						'desc'  => __( "URL to partner's landing page for the work.", 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'video'                => [
				'title'  => __( 'Video', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'           => [
						'type' => 'checkbox',
					],
					'name'             => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'The title of the video', 'wp-seo-structured-data-schema-pro' ),
					],
					'description'      => [
						'title'    => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'required' => true,
						'desc'     => __( 'The description of the video. New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],
					'thumbnailUrl'     => [
						'title'       => 'Thumbnail URL',
						'type'        => 'url',
						'placeholder' => 'URL',
						'required'    => true,
						'desc'        => __( 'A URL pointing to the video thumbnail image file. Images must be at least 160x90 pixels and at most 1920x1080 pixels.', 'wp-seo-structured-data-schema-pro' ),
					],
					'uploadDate'       => [
						'title'    => __( 'Updated date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'class'    => 'kcseo-date',
						'required' => true,
						'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'duration'         => [
						'title' => __( 'Duration', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The duration of the video in ISO 8601 format.(PT1M33S)', 'wp-seo-structured-data-schema-pro' ),
					],
					'contentUrl'       => [
						'title'       => 'Content URL',
						'type'        => 'url',
						'placeholder' => 'URL',
						'desc'        => __( 'A URL pointing to the actual video media file. This file should be in .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv, or other video file format.', 'wp-seo-structured-data-schema-pro' ),
					],
					'embedUrl'         => [
						'title'       => 'Embed URL',
						'placeholder' => 'URL',
						'type'        => 'url',
						'desc'        => __( 'A URL pointing to a player for the specific video. Usually this is the information in the src element of an < embed> tag.Example: Dailymotion: http://www.dailymotion.com/swf/x1o2g.', 'wp-seo-structured-data-schema-pro' ),
					],
					'interactionCount' => [
						'title' => __( 'Interaction count', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The number of times the video has been viewed.', 'wp-seo-structured-data-schema-pro' ),
					],
					'expires'          => [
						'title' => __( 'Expires', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'class' => 'kcseo-date',
						'desc'  => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'audio'                => [
				'title'  => __( 'Audio', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'         => [
						'type' => 'checkbox',
					],
					'name'           => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'The title of the audio', 'wp-seo-structured-data-schema-pro' ),
					],
					'description'    => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'The description of the audio. New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],
					'contentUrl'     => [
						'title'       => 'Content URL',
						'type'        => 'url',
						'placeholder' => 'URL',
						'required'    => true,
						'desc'        => esc_html__( 'A URL pointing to the actual audio media file. This file should be in .mp3, .wav, .mpc or other audio file format.', 'wp-seo-structured-data-schema-pro' ),
					],
					'duration'       => [
						'title' => __( 'Duration', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The duration of the audio in ISO 8601 format.(PT1M33S)', 'wp-seo-structured-data-schema-pro' ),
					],
					'encodingFormat' => [
						'type'  => 'text',
						'title' => esc_html__( 'Encoding Format', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( "The encoding format of audio like: 'audio/mpeg'", 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'faq'                  => [
				'title'  => __( 'FAQ Page', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'            => [
						'type' => 'checkbox',
					],
					'faq_items_heading' => [
						'type'  => 'heading',
						'title' => __( 'FAQ Questions & Answer', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => __( 'Please use either QAPage or FAQ schema. Both schemas at a time will give an error by Google.', 'wp-seo-structured-data-schema-pro' ),
					],
					'faq_items'         => [
						'title'     => __( 'FAQ item', 'wp-seo-structured-data-schema-pro' ),
						'type'      => 'group',
						'duplicate' => true,
						'fields'    => [
							'faq_item_heading' => [
								'type'  => 'heading',
								'title' => __( 'FAQ item', 'wp-seo-structured-data-schema-pro' ),
							],
							'question'         => [
								'title'    => __( 'Question', 'wp-seo-structured-data-schema-pro' ),
								'type'     => 'text',
								'required' => true,
							],
							'answer'           => [
								'title' => __( 'Answer', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'textarea',
							],
						],
					],
				],
			],
			'question'             => [
				'title'  => __( 'QAPage', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'                       => [
						'type' => 'checkbox',
					],
					'question_items_heading'       => [
						'type'  => 'heading',
						'title' => __( 'Question & AskAction schema', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => __( 'Please use either QAPage or FAQ schema. Both schemas at a time will give an error by Google.', 'wp-seo-structured-data-schema-pro' ),
					],
					'type'                         => [
						'title'    => __( 'Type', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'select',
						'options'  => [ 'Question', 'AskAction' ],
						'required' => true,
					],
					'question_author'              => [
						'title'       => __( 'Questionnaire author', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'required'    => true,
						'holderClass' => 'kcseo-faq-question-holder',
						'desc'        => __( 'Name of the questionnaire', 'wp-seo-structured-data-schema-pro' ),
					],
					'question'                     => [
						'title'       => __( 'Question', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'required'    => true,
						'holderClass' => 'kcseo-faq-question-holder',
						'desc'        => __( 'Short Question', 'wp-seo-structured-data-schema-pro' ),
					],
					'question_text'                => [
						'title'       => __( 'Question description', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'textarea',
						'required'    => true,
						'holderClass' => 'kcseo-faq-question-holder',
						'desc'        => __( 'The description of the question. New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],
					'question_dateCreated'         => [
						'title'       => __( 'Question created date', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'required'    => true,
						'class'       => 'kcseo-date',
						'holderClass' => 'kcseo-faq-question-holder',
						'desc'        => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'question_upvoteCount'         => [
						'title'       => __( 'Question up vote count', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'number',
						'holderClass' => 'kcseo-faq-question-holder',
					],
					'answerCount'                  => [
						'title'       => __( 'Answer count', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'number',
						'holderClass' => 'kcseo-faq-question-holder',
						'class'       => 'kcseo-question-a',
					],
					'accepted_answer'              => [
						'title'       => __( 'Accepted answer', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'textarea',
						'required'    => true,
						'holderClass' => 'kcseo-faq-question-holder',
						'desc'        => __( 'Accepted answer', 'wp-seo-structured-data-schema-pro' ),
					],
					'accepted_answer_dateCreated'  => [
						'title'       => __( 'Accepted answer created Date', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'required'    => true,
						'class'       => 'kcseo-date',
						'holderClass' => 'kcseo-faq-question-holder',
						'desc'        => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'accepted_answer_upvoteCount'  => [
						'title'       => __( 'Accepted answer up vote count', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'number',
						'holderClass' => 'kcseo-faq-question-holder',
					],
					'accepted_answer_author'       => [
						'title'       => __( 'Accepted answerer author', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'required'    => true,
						'holderClass' => 'kcseo-faq-question-holder',
						'desc'        => __( 'Name of the answerer', 'wp-seo-structured-data-schema-pro' ),
					],
					'accepted_answer_url'          => [
						'title'       => __( 'Accepted answerer URL', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'url',
						'required'    => true,
						'holderClass' => 'kcseo-faq-question-holder',
					],
					'suggested_answer'             => [
						'title'       => __( 'Suggested answer', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'textarea',
						'required'    => true,
						'holderClass' => 'kcseo-faq-question-holder',
						'desc'        => __( 'Suggested Answer', 'wp-seo-structured-data-schema-pro' ),
					],
					'suggested_answer_dateCreated' => [
						'title'       => __( 'Suggested answer created Date', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'required'    => true,
						'class'       => 'kcseo-date',
						'holderClass' => 'kcseo-faq-question-holder',
						'desc'        => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'suggested_answer_upvoteCount' => [
						'title'       => __( 'Suggested answer up vote count', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'number',
						'holderClass' => 'kcseo-faq-question-holder',
					],
					'suggested_answer_author'      => [
						'title'       => __( 'Suggested answerer author', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'required'    => true,
						'holderClass' => 'kcseo-faq-question-holder',
						'desc'        => __( 'Name of the answerer', 'wp-seo-structured-data-schema-pro' ),
					],
					'suggested_answer_url'         => [
						'title'       => __( 'Suggested answerer URL', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'url',
						'required'    => true,
						'holderClass' => 'kcseo-faq-question-holder',
					],
					'agent'                        => [
						'title'       => __( 'Agent Author', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'required'    => true,
						'holderClass' => 'kcseo-faq-ask-action-holder',
						'desc'        => __( 'Name of the questionnaire', 'wp-seo-structured-data-schema-pro' ),
					],
					'recipient'                    => [
						'title'       => __( 'Recipient Author', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'required'    => true,
						'holderClass' => 'kcseo-faq-ask-action-holder',
						'desc'        => __( 'Name of the recipient', 'wp-seo-structured-data-schema-pro' ),
					],
					'ask_action_question'          => [
						'title'       => __( 'Question', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'required'    => true,
						'holderClass' => 'kcseo-faq-ask-action-holder',
						'desc'        => __( 'Question of the AskAction', 'wp-seo-structured-data-schema-pro' ),
					],
					'ask_action_answer'            => [
						'title'       => __( 'Answer', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'required'    => true,
						'holderClass' => 'kcseo-faq-ask-action-holder kcseo-faq-ask-action-answer-holder',
						'desc'        => __( 'Answer of the AskAction', 'wp-seo-structured-data-schema-pro' ),
					],
				],
			],
			'itemList'             => [
				'title'  => __( 'Item List', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'        => [
						'type' => 'checkbox',
					],
					'ItemListOrder' => [
						'title'    => __( 'Item List Order Type', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'select',
						'required' => true,
						'options'  => [
							'ItemListOrderAscending'  => 'Ascending',
							'ItemListOrderDescending' => 'Descending',
							'ItemListUnordered'       => 'Unordered',
						],
					],
					'numberOfItems' => [
						'title' => __( 'Number Of Items', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
					],
					'url'           => [
						'title'    => __( 'URL', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'url',
						'required' => true,
					],
					'name'          => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'description'   => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => esc_html__( 'New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],
					'list_items'    => [
						'title'     => __( 'List items', 'wp-seo-structured-data-schema-pro' ),
						'type'      => 'group',
						'duplicate' => true,
						'fields'    => [
							'list_item_heading' => [
								'type'  => 'heading',
								'title' => __( 'List Item', 'wp-seo-structured-data-schema-pro' ),
							],
							'name'              => [
								'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
								'type'     => 'text',
								'required' => true,
							],
							'position'          => [
								'title'    => __( 'Position', 'wp-seo-structured-data-schema-pro' ),
								'type'     => 'number',
								'required' => true,
							],
							'url'               => [
								'title'    => __( 'URL', 'wp-seo-structured-data-schema-pro' ),
								'type'     => 'url',
								'required' => true,
								'desc'     => __( 'Every list url should be different url at same domain <br> 1. http://example.com/post/tv , 2. http://example.com/post/radio', 'wp-seo-structured-data-schema-pro' ),
							],
							'image'             => [
								'title'    => __( 'Image', 'wp-seo-structured-data-schema-pro' ),
								'type'     => 'image',
								'required' => true,
							],
							'description'       => [
								'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'textarea',
								'desc'  => esc_html__( 'New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
							],
						],
					],
				],
			],
			'vacationRental'       => [
				'title'  => __( 'Vacation Rental', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'                   => [
						'type' => 'checkbox',
					],
					'additionalType'           => [
						'title'       => __( 'Additional Type', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'recommended' => true,
						'desc'        => __( 'Default: HolidayVillageRental. Check Docs: ', 'wp-seo-structured-data-schema-pro' ) . '<a target="_blank" href="https://developers.google.com/search/docs/appearance/structured-data/vacation-rental">Vacation Rental</a>',
					],
					'name'                     => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'description'              => [
						'title'       => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'recommended' => true,
					],
					'priceRange'               => [
						'title' => __( 'Price Range', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Ex: $200 - $500 per night', 'wp-seo-structured-data-schema-pro' ),
					],
					'telephone'                => [
						'title' => __( 'Telephone', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'identifier'               => [
						'title'    => __( 'Identifier', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'latitude'                 => [
						'title'    => __( 'Latitude', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'longitude'                => [
						'title'    => __( 'Longitude', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					// 'servesCuisine'   => 'American',
					'containsPlace'            => [
						'title' => __( 'Contains Place', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
					],
					'containsPlaceType'        => [
						'title'   => __( 'Contains Place Additional Type', 'wp-seo-structured-data-schema-pro' ),
						'type'    => 'select',
						'empty'   => 'Select one',
						'options' => [
							'EntirePlace' => 'EntirePlace',
							'PrivateRoom' => 'PrivateRoom',
							'SharedRoom'  => 'SharedRoom',
						],
					],
					'occupancy'                => [
						'title' => __( 'Occupancy Value', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'The numerical value of guests allowed to stay at the vacation rental listing.', 'wp-seo-structured-data-schema-pro' ),
					],
					'numberOfBathroomsTotal'   => [
						'title' => __( 'The Total Number Of Bathrooms', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
					],
					'numberOfBedrooms'         => [
						'title' => __( 'The Total number Number Of Bedrooms', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
					],
					'numberOfRooms'            => [
						'title' => __( 'The Total number Number Of Rooms', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
					],
					'floorSize'                => [
						'title' => __( 'Floor Size', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
					],
					'floorSizeValue'           => [
						'title' => __( 'Value', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
					],
					'unitCode'                 => [
						'title' => __( 'UnitCode', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'EX: MTK', 'wp-seo-structured-data-schema-pro' ),
					],

					// beds.
					'beds'                     => [
						'required'  => true,
						'type'      => 'group',
						'title'     => esc_html__( 'Bed', 'wp-seo-structured-data-schema-pro' ),
						'duplicate' => true,
						'fields'    => [
							'review_heading' => [
								'title' => __( 'Bed', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'heading',
							],
							'numberOfBeds'   => [
								'title' => __( 'Number Of Beds', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'number',
								'attr'  => 'step="any" min="1"',
							],
							'typeOfBed'      => [
								'title' => __( 'Bed Type', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'Check Details For typeOfBed: ', 'wp-seo-structured-data-schema-pro' ) . '<a target="_blank" href="https://developers.google.com/search/docs/appearance/structured-data/vacation-rental">Vacation Rental </a>',
							],
						],
					],
					// beds.
					'amenityFeature'           => [
						'required'  => true,
						'type'      => 'group',
						'title'     => esc_html__( 'Amenity Feature', 'wp-seo-structured-data-schema-pro' ),
						'duplicate' => true,
						'fields'    => [
							'review_heading' => [
								'title' => __( 'Amenity Feature', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'heading',
							],
							'feature'        => [
								'title' => __( 'Feature', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'Check Details For amenityFeature: ', 'wp-seo-structured-data-schema-pro' ) . '<a target="_blank" href="https://developers.google.com/search/docs/appearance/structured-data/vacation-rental">Vacation Rental </a>',
							],
							'value'          => [
								'title' => __( 'Available?', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'checkbox',
								'desc'  => __( 'checkmark (✓) if the feature is available', 'wp-seo-structured-data-schema-pro' ),
							],
						],
					],

					// Aggregate Rating.
					'aggregate_rating_section' => [
						'title' => __( 'Aggregate Rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
					],
					'aggregate_ratingValue'    => [
						'title' => __( 'Rating value', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'aggregate_bestRating'     => [
						'title' => __( 'Best rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'aggregate_worstRating'    => [
						'title' => __( 'Worst rating', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],
					'aggregate_ratingCount'    => [
						'title' => __( 'Rating Count', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
						'attr'  => 'step="any"',
						'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
					],

					// review.
					'reviews'                  => [
						'required'  => true,
						'type'      => 'group',
						'title'     => esc_html__( 'Reviews', 'wp-seo-structured-data-schema-pro' ),
						'duplicate' => true,
						'fields'    => [
							'review_heading' => [
								'title' => __( 'Review', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'heading',
							],
							'author'         => [
								'title'    => __( 'Author', 'wp-seo-structured-data-schema-pro' ),
								'type'     => 'text',
								'required' => true,
							],
							'ratingValue'    => [
								'title' => __( 'Rating value', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'number',
								'attr'  => 'step="any"',
								'desc'  => __( 'A numerical quality rating for the item.', 'wp-seo-structured-data-schema-pro' ),
							],
							'bestRating'     => [
								'title' => __( 'Best rating', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'number',
								'attr'  => 'step="any"',
								'desc'  => __( 'The highest value allowed in this rating system.', 'wp-seo-structured-data-schema-pro' ),
							],
							'datePublished'  => [
								'title'    => __( 'Published date', 'wp-seo-structured-data-schema-pro' ),
								'type'     => 'text',
								'class'    => 'kcseo-date',
								'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
								'required' => true,
							],
						],
					],
					// Address.
					'PostalAddress'            => [
						'title' => __( 'Address', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
					],
					'streetAddress'            => [
						'title' => __( 'Street Address', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'addressLocality'          => [
						'title' => __( 'Address Locality', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'region'                   => [
						'title' => __( 'Region', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Ex: CA ', 'wp-seo-structured-data-schema-pro' ),
					],
					'postalCode'               => [
						'title' => __( 'Postal Code', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
					],
					'addressCountry'           => [
						'title' => __( 'Country', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Ex: US ', 'wp-seo-structured-data-schema-pro' ),
					],
					'images'                   => [
						'required'  => true,
						'type'      => 'group',
						'title'     => esc_html__( 'Images', 'wp-seo-structured-data-schema-pro' ),
						'duplicate' => true,
						'fields'    => [
							'images_heading' => [
								'title' => __( 'Images', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'heading',
								'desc'  => __( 'One or more images of the listing. The listing must have a minimum of 8 photos (at least 1 image of each of the following: bedroom, bathroom, and common area)', 'wp-seo-structured-data-schema-pro' ),
							],
							'image'          => [
								'title' => __( 'Upload Images', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'image',
							],
						],
					],

				],
			],
			'vehicleListing'       => [
				'title'  => __( 'Vehicle Listing', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'                  => [
						'type' => 'checkbox',
					],
					'type'                    => [
						'title'    => __( 'Vehicle Type', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => esc_html__( 'Type Look like: BusOrCoach, Car, Motorcycle, MotorizedBicycle. Ex: Car.  More Details:', 'wp-seo-structured-data-schema-pro' ) . "<a href='https://schema.org/Vehicle' target='_blank'>" . esc_html__( 'Vehicle', 'wp-seo-structured-data-schema-pro' ) . '</a>',
					],
					'name'                    => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'IdentificationNumber'    => [
						'title'    => __( 'Identification Number', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'url'                     => [
						'title' => esc_html__( 'URL', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'url',
					],
					'description'             => [
						'title' => __( 'Description', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'textarea',
						'desc'  => __( 'Short description. New line is not supported.', 'wp-seo-structured-data-schema-pro' ),
					],

					'pricing_section_heading' => [
						'title' => __( 'Offer', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
					],
					'offers_price'            => [
						'title' => __( 'Price', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
					],
					'priceCurrency'           => [
						'title' => __( 'Price Currency', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The 3-letter currency code.', 'wp-seo-structured-data-schema-pro' ),
					],
					'priceValidUntil'         => [
						'title'       => __( 'PriceValidUntil', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'recommended' => true,
						'class'       => 'kcseo-date',
						'desc'        => __( 'The date (in ISO 8601 date format) after which the price will no longer be available. Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
					],
					'availability'            => [
						'title'   => 'Availability',
						'type'    => 'select',
						'empty'   => 'Select one',
						'options' => [
							'http://schema.org/InStock'    => 'InStock',
							'http://schema.org/InStoreOnly' => 'InStoreOnly',
							'http://schema.org/OutOfStock' => 'OutOfStock',
							'http://schema.org/SoldOut'    => 'SoldOut',
							'http://schema.org/OnlineOnly' => 'OnlineOnly',
							'http://schema.org/LimitedAvailability' => 'LimitedAvailability',
							'http://schema.org/Discontinued' => 'Discontinued',
							'http://schema.org/PreOrder'   => 'PreOrder',
						],
						'desc'    => __( 'Select a availability type', 'wp-seo-structured-data-schema-pro' ),
					],
					'itemCondition'           => [
						'title'    => __( 'Item Condition', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'select',
						'required' => true,
						'options'  => [
							'https://schema.org/NewCondition'  => 'New',
							'https://schema.org/UsedCondition' => 'Used',
						],
					],
					'brand'                   => [
						'title'    => __( 'Brand', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'The brand of the product (Used globally).', 'wp-seo-structured-data-schema-pro' ),
					],
					'model'                   => [
						'title'    => __( 'Model', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'vehicleConfiguration'    => [
						'title'    => __( 'Configuration', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
						'desc'     => __( 'The trim of the model, such as S, SV, or SL..', 'wp-seo-structured-data-schema-pro' ),
					],
					'vehicleModelDate'        => [
						'title'    => __( 'Model Date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'Mileage'                 => [
						'title' => __( 'Mileage', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'heading',
					],
					'mileageFromOdometer'     => [
						'title'    => __( 'Mileage From Odometer', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'required' => true,
					],
					'unitCode'                => [
						'title' => __( 'Unit Code', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Use one of the following values: ( For miles: SMI For kilometers: KMT ).', 'wp-seo-structured-data-schema-pro' ),
					],

					'color'                   => [
						'title' => __( 'Color', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The OEM-specified exterior color, such as White, Platinum, or Metallic Tri-Coat.', 'wp-seo-structured-data-schema-pro' ),
					],
					'vehicleInteriorColor'    => [
						'title' => __( 'Interior Color', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The OEM-specified interior color, such as Brown or Ivory.', 'wp-seo-structured-data-schema-pro' ),
					],
					'vehicleInteriorType'     => [
						'title' => __( 'Interior Type', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The type or material of the interior of the vehicle (for example, synthetic fabric, leather, wood).', 'wp-seo-structured-data-schema-pro' ),
					],
					'bodyType'                => [
						'title' => __( 'Body Type', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'Check Bodytype: ', 'wp-seo-structured-data-schema-pro' ) . "<a href='https://developers.google.com/search/docs/appearance/structured-data/vehicle-listing' target='_blank'>" . esc_html__( 'Body Type', 'wp-seo-structured-data-schema-pro' ) . '</a>',
					],
					'driveWheelConfiguration' => [
						'title'    => __( 'Drive Wheel Configuration', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'select',
						'required' => true,
						'options'  => [
							'https://schema.org/AllWheelDriveConfiguration'  => 'AllWheel',
							'https://schema.org/FourWheelDriveConfiguration'  => 'FourWheel',
							'https://schema.org/FrontWheelDriveConfiguration'  => 'FrontWheel',
							'https://schema.org/RearWheelDriveConfiguration'  => 'RearWheel',
						],
					],
					'fuelType'                => [
						'title' => __( 'Engine Fuel Type', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The type of fuel that\'s suitable for the engine of the vehicle.', 'wp-seo-structured-data-schema-pro' ),
					],
					'vehicleTransmission'     => [
						'title' => __( 'Transmission', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'text',
						'desc'  => __( 'The transmission specification. For example, 9-speed automatic or manual.', 'wp-seo-structured-data-schema-pro' ),
					],
					'numberOfDoors'           => [
						'title' => __( 'Number Of Doors', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
					],
					'vehicleSeatingCapacity'  => [
						'title' => __( 'Seating Capacity', 'wp-seo-structured-data-schema-pro' ),
						'type'  => 'number',
					],

					'images'                  => [
						'required'  => true,
						'type'      => 'group',
						'title'     => esc_html__( 'Images', 'wp-seo-structured-data-schema-pro' ),
						'duplicate' => true,
						'fields'    => [
							'images_heading' => [
								'title' => __( 'Images', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'heading',
								'desc'  => __( 'One or more images of the listing. The listing must have a minimum of 8 photos (at least 1 image of each of the following: bedroom, bathroom, and common area)', 'wp-seo-structured-data-schema-pro' ),
							],
							'image'          => [
								'title' => __( 'Upload Images', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'image',
							],
						],
					],

					'shipping_details'        => [
						'type'  => 'heading',
						'title' => esc_html__( 'Shipping Details', 'wp-seo-structured-data-schema-pro' ),
					],
					'shippingRate'            => [
						'type'  => 'number',
						'title' => esc_html__( 'Shipping Rate ( Price )', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'Shipping Cost.', 'wp-seo-structured-data-schema-pro' ),

					],
					'shippingDestination'     => [
						'type'  => 'text',
						'attr'  => 'placeholder="US"',
						'title' => esc_html__( 'Shipping Destination', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'The two-letter country code, in ISO 3166-1 alpha-2 format.', 'wp-seo-structured-data-schema-pro' ),
					],
					'addressRegion'           => [
						'type'  => 'text',
						'attr'  => 'placeholder="NY", "AL", "AK"',
						'title' => esc_html__( 'Address Region', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'If you include this property, the region must be a 2- or 3-digit ISO 3166-2 subdivision code, without country prefix. Currently, Google Search only supports the US, Australia, and Japan. Examples: "NY" (for US, state of New York), "NSW" (for Australia, state of New South Wales), or "03" (for Japan, Iwate prefecture).Example: "NY", "AL", "AK".', 'wp-seo-structured-data-schema-pro' ),
					],
					'handlingTime'            => [
						'type'  => 'heading',
						'title' => esc_html__( 'Handling Time', 'wp-seo-structured-data-schema-pro' ),
					],
					'handlingTimeMinimum'     => [
						'type'  => 'number',
						'title' => esc_html__( 'Handling Time Minimum ( Days )', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'Minimum days for handling time.', 'wp-seo-structured-data-schema-pro' ),
					],
					'handlingTimeMaximum'     => [
						'type'  => 'number',
						'title' => esc_html__( 'Handling Time Maximum (Days)', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'Maximum days for handling time.', 'wp-seo-structured-data-schema-pro' ),
					],
					'transitTimeMinimum'      => [
						'type'  => 'number',
						'title' => esc_html__( 'Transit Time Minimum ( Days )', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'Minimum days for Transit Time.', 'wp-seo-structured-data-schema-pro' ),
					],
					'transitTimeMaximum'      => [
						'type'  => 'number',
						'title' => esc_html__( 'Transit Time Maximum ( Days )', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'Maximum days for Transit Time.', 'wp-seo-structured-data-schema-pro' ),
					],

					'MerchantReturnPolicy'    => [
						'type'  => 'heading',
						'title' => esc_html__( 'Merchant Return Policy', 'wp-seo-structured-data-schema-pro' ),
					],

					'applicableCountry'       => [
						'type'  => 'text',
						'attr'  => 'placeholder="US"',
						'title' => esc_html__( 'Applicable Country', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'The two-letter country code, in ISO 3166-1 alpha-2 format.', 'wp-seo-structured-data-schema-pro' ),
					],

					'merchantReturnDays'      => [
						'type'  => 'number',
						'title' => esc_html__( 'Merchant Return Days', 'wp-seo-structured-data-schema-pro' ),
					],

                    'rating_section'       => [
                        'title' => __( 'Review & Rating', 'wp-seo-structured-data-schema-pro' ),
                        'type'  => 'heading',
                    ],
                    'reviewRatingValue'    => [
                        'title'       => __( 'Review rating value', 'wp-seo-structured-data-schema-pro' ),
                        'type'        => 'number',
                        'recommended' => true,
                        'attr'        => 'step="any"',
                        'desc'        => __( 'Rating value. (1 , 2.5, 3, 5 etc)', 'wp-seo-structured-data-schema-pro' ),
                    ],
                    'reviewBestRating'     => [
                        'title'       => __( 'Review Best rating', 'wp-seo-structured-data-schema-pro' ),
                        'type'        => 'number',
                        'recommended' => true,
                        'attr'        => 'step="any"',
                    ],
                    'reviewWorstRating'    => [
                        'title'       => __( 'Review Worst rating', 'wp-seo-structured-data-schema-pro' ),
                        'type'        => 'number',
                        'recommended' => true,
                        'attr'        => 'step="any"',
                    ],
                    'reviewAuthor'         => [
                        'title' => __( 'Review author', 'wp-seo-structured-data-schema-pro' ),
                        'type'  => 'text',
                    ],
                    'ratingValue'          => [
                        'title'       => __( 'Aggregate Rating value', 'wp-seo-structured-data-schema-pro' ),
                        'type'        => 'number',
                        'recommended' => true,
                        'attr'        => 'step="any"',
                        'desc'        => __( 'Rating value. (1 , 2.5, 3, 5 etc)', 'wp-seo-structured-data-schema-pro' ),
                    ],
                    'reviewCount'          => [
                        'title' => __( 'Aggregate Total review count', 'wp-seo-structured-data-schema-pro' ),
                        'type'  => 'number',
                        'attr'  => 'step="any"',
                        'desc'  => __( "Review Count. <span class='required'>This is required if (Rating value) is given</span>", 'wp-seo-structured-data-schema-pro' ),
                    ],

				],
			],
			'specialAnnouncement'  => [
				'title'  => __( 'Special Announcement', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'        => [
						'type' => 'checkbox',
					],
					'name'          => [
						'title'    => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'desc'     => __( 'SpecialAnnouncement.name: Name of the announcement. This text should be present on the underlying page.', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'url'           => [
						'title'    => __( 'Page URL', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'url',
						'desc'     => __( 'SpecialAnnouncement.url: URL of the page containing the announcements. If present, this must match the URL of the page containing the information.', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'datePublished' => [
						'title'    => __( 'Published date', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'text',
						'class'    => 'kcseo-date',
						'desc'     => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'expires'       => [
						'title'       => __( 'Expires date', 'wp-seo-structured-data-schema-pro' ),
						'type'        => 'text',
						'class'       => 'kcseo-date',
						'desc'        => __( 'Like this: 2024-01-05T08:00:00+08:00', 'wp-seo-structured-data-schema-pro' ),
						'recommended' => true,
					],
					'text'          => [
						'title'    => __( 'Text', 'wp-seo-structured-data-schema-pro' ),
						'type'     => 'textarea',
						'desc'     => __( 'SpecialAnnouncement.text: Text of the announcements.', 'wp-seo-structured-data-schema-pro' ),
						'required' => true,
					],
					'locations'     => [
						'title'     => __( 'Announcement Locations', 'wp-seo-structured-data-schema-pro' ),
						'type'      => 'group',
						'duplicate' => true,
						'fields'    => [
							'location_heading'  => [
								'type'  => 'heading',
								'title' => __( 'Announcement Location', 'wp-seo-structured-data-schema-pro' ),
							],
							'type'              => [
								'title'    => __( 'Type', 'wp-seo-structured-data-schema-pro' ),
								'type'     => 'select',
								'options'  => self::announcementLocationTypes(),
								'required' => true,
							],
							'name'              => [
								'title'       => __( 'Name', 'wp-seo-structured-data-schema-pro' ),
								'type'        => 'text',
								'desc'        => __( 'SpecialAnnouncement.announcementLocation.name: ', 'wp-seo-structured-data-schema-pro' ),
								'recommended' => true,
							],
							'url'               => [
								'title'       => __( 'URL', 'wp-seo-structured-data-schema-pro' ),
								'type'        => 'url',
								'recommended' => true,
								'desc'        => __( 'SpecialAnnouncement.announcementLocation.url: URL', 'wp-seo-structured-data-schema-pro' ),
							],
							'address_street'    => [
								'title'       => __( 'Address: Street', 'wp-seo-structured-data-schema-pro' ),
								'type'        => 'text',
								'desc'        => __( 'SpecialAnnouncement.announcementLocation.address.streetAddress: The street address. For example, 1600 Amphitheatre Pkwy.', 'wp-seo-structured-data-schema-pro' ),
								'recommended' => true,
							],
							'address_locality'  => [
								'title'       => __( 'Address: Locality', 'wp-seo-structured-data-schema-pro' ),
								'type'        => 'text',
								'desc'        => __( 'SpecialAnnouncement.announcementLocation.address.addressLocality: The locality in which the street address is, and which is in the region. For example, Mountain View.', 'wp-seo-structured-data-schema-pro' ),
								'recommended' => true,
							],
							'address_post_code' => [
								'title'       => __( 'Address: Post Code', 'wp-seo-structured-data-schema-pro' ),
								'type'        => 'text',
								'desc'        => __( 'SpecialAnnouncement.announcementLocation.address.postalCode: The postal code. For example, 94043.', 'wp-seo-structured-data-schema-pro' ),
								'recommended' => true,
							],
							'address_region'    => [
								'title'       => __( 'Address: Region', 'wp-seo-structured-data-schema-pro' ),
								'type'        => 'text',
								'desc'        => __( 'SpecialAnnouncement.announcementLocation.address.addressRegion: The region in which the locality is, and which is in the country. For example, California.', 'wp-seo-structured-data-schema-pro' ),
								'recommended' => true,
							],
							'address_country'   => [
								'title'       => __( 'Address: Country', 'wp-seo-structured-data-schema-pro' ),
								'type'        => 'text',
								'desc'        => __( 'SpecialAnnouncement.announcementLocation.address.addressCountry: The country. For example, USA. You can also provide the two-letter ISO 3166-1 alpha-2 country code.', 'wp-seo-structured-data-schema-pro' ),
								'recommended' => true,
							],
							'id'                => [
								'title' => __( 'ID', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'text',
								'desc'  => __( 'SpecialAnnouncement.announcementLocation.@id: An optional unique identifier so that you can reference pre-existing structured data for this location.', 'wp-seo-structured-data-schema-pro' ),
							],
							'image'             => [
								'title' => __( 'Image', 'wp-seo-structured-data-schema-pro' ),
								'type'  => 'image',
							],
							'priceRange'        => [
								'title'       => 'Price Range (Recommended)',
								'type'        => 'text',
								'recommended' => true,
								'desc'        => __( 'The price range of the business, for example $$$.', 'wp-seo-structured-data-schema-pro' ),
							],
							'telephone'         => [
								'title'       => 'Telephone (Recommended)',
								'type'        => 'text',
								'recommended' => true,
							],
						],
					],
				],
			],
			'manual_schema'        => [
				'title'  => __( 'Custom Schema', 'wp-seo-structured-data-schema-pro' ),
				'fields' => [
					'active'                 => [
						'type' => 'checkbox',
					],
					'generated_snippet_code' => [
						'type'  => 'textarea',
						'class' => 'kcseo-menual-snippet',
						'title' => esc_html__( 'Menual Snippet JSON String', 'wp-seo-structured-data-schema-pro' ),
						'desc'  => esc_html__( 'HTML tags is not allowed. Please "Test your structured" data any generated JSON snippet before implementation. Visit for validation: ', 'wp-seo-structured-data-schema-pro' ) . "<a href='https://validator.schema.org/' target='_blank'>" . esc_html__( 'Test your structured data', 'wp-seo-structured-data-schema-pro' ) . '</a>' . esc_html__( 'For multiple schema please use this formate [{"@context": "https://schema.org"},{"@context": "https://schema.org"}]', 'wp-seo-structured-data-schema-pro' ) ,
					],
				],
			],
		];

		return apply_filters( 'kcseo_schema_types', $schemas );
	}

	static function getSiteTypes() {
		$siteTypes = [
			'Organization',
			'LocalBusiness'  => [
				'AnimalShelter',
				'AutomotiveBusiness'          => [
					'AutoBodyShop',
					'AutoDealer',
					'AutoPartsStore',
					'AutoRental',
					'AutoRepair',
					'AutoWash',
					'GasStation',
					'MotorcycleDealer',
					'MotorcycleRepair',
				],
				'ChildCare',
				'DryCleaningOrLaundry',
				'EmergencyService',
				'EmploymentAgency',
				'EntertainmentBusiness'       => [
					'AdultEntertainment',
					'AmusementPark',
					'ArtGallery',
					'Casino',
					'ComedyClub',
					'MovieTheater',
					'NightClub',

				],
				'FinancialService'            => [
					'AccountingService',
					'AutomatedTeller',
					'BankOrCreditUnion',
					'InsuranceAgency',
				],
				'FoodEstablishment'           => [
					'Bakery',
					'BarOrPub',
					'Brewery',
					'CafeOrCoffeeShop',
					'FastFoodRestaurant',
					'IceCreamShop',
					'Restaurant',
					'Winery',
				],
				'GovernmentOffice',
				'HealthAndBeautyBusiness'     => [
					'BeautySalon',
					'DaySpa',
					'HairSalon',
					'HealthClub',
					'NailSalon',
					'TattooParlor',
				],
				'HomeAndConstructionBusiness' => [
					'Electrician',
					'GeneralContractor',
					'HVACBusiness',
					'HousePainter',
					'Locksmith',
					'MovingCompany',
					'Plumber',
					'RoofingContractor',
				],
				'InternetCafe',
				'LegalService'                => [
					'Attorney',
					'Notary',
				],
				'Library',
				'MedicalBusiness'             => [
					'CommunityHealth',
					'Dentist',
					'Dermatology',
					'DietNutrition',
					'Emergency',
					'Geriatric',
					'Gynecologic',
					'MedicalClinic',
					'Midwifery',
					'Nursing',
					'Obstetric',
					'Oncologic',
					'Optician',
					'Optometric',
					'Otolaryngologic',
					'Pediatric',
					'Pharmacy',
					'Physician',
					'Physiotherapy',
					'PlasticSurgery',
					'Podiatric',
					'PrimaryCare',
					'Psychiatric',
					'PublicHealth',
				],
				'LodgingBusiness'             => [
					'BedAndBreakfast',
					'Campground',
					'Hostel',
					'Hotel',
					'Motel',
					'Resort',
				],
				'ProfessionalService',
				'RadioStation',
				'RealEstateAgent',
				'RecyclingCenter',
				'SelfStorage',
				'ShoppingCenter',
				'SportsActivityLocation'      => [
					'BowlingAlley',
					'ExerciseGym',
					'GolfCourse',
					'HealthClub',
					'PublicSwimmingPool',
					'SkiResort',
					'SportsClub',
					'StadiumOrArena',
					'TennisComplex',
				],
				'Store'                       => [
					'AutoPartsStore',
					'BikeStore',
					'BookStore',
					'ClothingStore',
					'ComputerStore',
					'ConvenienceStore',
					'DepartmentStore',
					'ElectronicsStore',
					'Florist',
					'FurnitureStore',
					'GardenStore',
					'GroceryStore',
					'HardwareStore',
					'HobbyShop',
					'HomeGoodsStore',
					'JewelryStore',
					'LiquorStore',
					'MensClothingStore',
					'MobilePhoneStore',
					'MovieRentalStore',
					'MusicStore',
					'OfficeEquipmentStore',
					'OutletStore',
					'PawnShop',
					'PetStore',
					'ShoeStore',
					'SportingGoodsStore',
					'TireShop',
					'ToyStore',
					'WholesaleStore',
				],
				'TelevisionStation',
				'TouristInformationCenter',
				'TravelAgency',
				'TaxiService',
			],
			'NGO'            => [],
			'CivicStructure' => [
				'Museum',
			],
		];

		return apply_filters( 'kcseo_site_types', $siteTypes );
	}

	static function getCountryList() {
		$countryList = [
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua and Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia, Plurinational State of',
			'BQ' => 'Bonaire, Sint Eustatius and Saba',
			'BA' => 'Bosnia and Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CG' => 'Congo',
			'CD' => 'Congo, the Democratic Republic of the',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Côte d Ivoire',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CW' => 'Curaçao',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands (Malvinas)',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island and McDonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran, Islamic Republic of',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KP' => "Korea, Democratic People's Republic of",
			'KR' => 'Korea, Republic of,',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Lao Peoples Democratic Republic',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia, the former Yugoslav Republic of',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia, Federated States of',
			'MD' => 'Moldova, Republic of',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestine, State of',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthélemy',
			'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
			'KN' => 'Saint Kitts and Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin (French part)',
			'PM' => 'Saint Pierre and Miquelon',
			'VC' => 'Saint Vincent and the Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome and Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SX' => 'Sint Maarten (Dutch part)',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia and the South Sandwich Islands',
			'SS' => 'South Sudan',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard and Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan, Province of China',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania, United Republic of',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad and Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks and Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Minor Outlying Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela, Bolivarian Republic of',
			'VN' => 'Viet Nam',
			'VG' => 'Virgin Islands, British',
			'VI' => 'Virgin Islands, U.S.',
			'WF' => 'Wallis and Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		];

		return apply_filters( 'kcseo_country_list', $countryList );
	}

	static function getContactTypes() {
		$contact_types = [
			'customer service',
			'customer support',
			'technical support',
			'billing support',
			'bill payment',
			'sales',
			'reservations',
			'credit card support',
			'emergency',
			'baggage tracking',
			'roadside assistance',
			'package tracking',
		];

		return apply_filters( 'kcseo_contact_types', $contact_types );
	}

	static function getLanguageList() {
		$language_list = [
			'Akan',
			'Amharic',
			'Arabic',
			'Assamese',
			'Awadhi',
			'Azerbaijani',
			'Balochi',
			'Belarusian',
			'Bengali',
			'Bhojpuri',
			'Burmese',
			'Cantonese',
			'Cebuano',
			'Chewa',
			'Chhattisgarhi',
			'Chittagonian',
			'Czech',
			'Deccan',
			'Dhundhari',
			'Dutch',
			'English',
			'French',
			'Fula',
			'Gan',
			'German',
			'Greek',
			'Gujarati',
			'Haitian Creole',
			'Hakka',
			'Haryanvi',
			'Hausa',
			'Hiligaynon',
			'Hindi / Urdu',
			'Hmong',
			'Hungarian',
			'Igbo',
			'Ilokano',
			'Italian',
			'Japanese',
			'Javanese',
			'Jin',
			'Kannada',
			'Kazakh',
			'Khmer',
			'Kinyarwanda',
			'Kirundi',
			'Konkani',
			'Korean',
			'Kurdish',
			'Madurese',
			'Magahi',
			'Maithili',
			'Malagasy',
			'Malay/Indonesian',
			'Malayalam',
			'Mandarin',
			'Marathi',
			'Marwari',
			'Min Bei',
			'Min Dong',
			'Min Nan',
			'Mossi',
			'Nepali',
			'Oriya',
			'Oromo',
			'Pashto',
			'Persian',
			'Polish',
			'Portuguese',
			'Punjabi',
			'Quechua',
			'Romanian',
			'Russian',
			'Saraiki',
			'Serbo-Croatian',
			'Shona',
			'Sindhi',
			'Sinhalese',
			'Somali',
			'Spanish',
			'Sundanese',
			'Swahili',
			'Swedish',
			'Sylheti',
			'Tagalog',
			'Tamil',
			'Telugu',
			'Thai',
			'Turkish',
			'Ukrainian',
			'Uyghur',
			'Uzbek',
			'Vietnamese',
			'Wu',
			'Xhosa',
			'Xiang',
			'Yoruba',
			'Zulu',
		];

		return apply_filters( 'kcseo_language_list', $language_list );
	}

	static function getSocialList() {
		$socialList = [
			'facebook'   => __( 'Facebook', 'wp-seo-structured-data-schema-pro' ),
			'twitter'    => __( 'X ( Formerly Twitter )', 'wp-seo-structured-data-schema-pro' ),
			// 'google-plus' => __('Google+', "wp-seo-structured-data-schema-pro"),
			'instagram'  => __( 'Instagram', 'wp-seo-structured-data-schema-pro' ),
			'youtube'    => __( 'Youtube', 'wp-seo-structured-data-schema-pro' ),
			'linkedin'   => __( 'LinkedIn', 'wp-seo-structured-data-schema-pro' ),
			'myspace'    => __( 'Myspace', 'wp-seo-structured-data-schema-pro' ),
			'pinterest'  => __( 'Pinterest', 'wp-seo-structured-data-schema-pro' ),
			'soundcloud' => __( 'SoundCloud', 'wp-seo-structured-data-schema-pro' ),
			'tumblr'     => __( 'Tumblr', 'wp-seo-structured-data-schema-pro' ),
			'wikidata'   => __( 'Wikidata', 'wp-seo-structured-data-schema-pro' ),
			'tiktok'     => __( 'TikTok', 'wp-seo-structured-data-schema-pro' ),
		];

		return apply_filters( 'kcseo_social_list', $socialList );
	}

	static function getApplicationCategoryList() {

		$list = [
			'GameApplication',
			'SocialNetworkingApplication',
			'TravelApplication',
			'ShoppingApplication',
			'SportsApplication',
			'LifestyleApplication',
			'BusinessApplication',
			'DesignApplication',
			'DeveloperApplication',
			'DriverApplication',
			'EducationalApplication',
			'HealthApplication',
			'FinanceApplication',
			'SecurityApplication',
			'BrowserApplication',
			'CommunicationApplication',
			'DesktopEnhancementApplication',
			'EntertainmentApplication',
			'MultimediaApplication',
			'HomeApplication',
			'UtilitiesApplication',
			'ReferenceApplication',
		];
		return apply_filters( 'kcseo_application_category_list', $list );
	}

	static function announcementLocationTypes() {
		return apply_filters(
			'kcseo_announcement_location_types',
			[
				'Airport',
				'Aquarium',
				'Beach',
				'Bridge',
				'BuddhistTemple',
				'BusStation',
				'BusStop',
				'Campground',
				'CatholicChurch',
				'Cemetery',
				'Church',
				'CivicStructure',
				'CityHall',
				'CollegeOrUniversity',
				'Courthouse',
				'CovidTestingFacility',
				'Crematorium',
				'DefenceEstablishment',
				'EducationalOrganization',
				'ElementarySchool',
				'Embassy',
				'EventVenue',
				'FireStation',
				'GovernmentBuilding',
				'HighSchool',
				'HinduTemple',
				'Hospital',
				'LegislativeBuilding',
				'MiddleSchool',
				'Mosque',
				'MovieTheater',
				'Museum',
				'MusicVenue',
				'Park',
				'ParkingFacility',
				'PerformingArtsTheater',
				'PlaceOfWorship',
				'Playground',
				'PoliceStation',
				'Preschool',
				'RVPark',
				'School',
				'StadiumOrArena',
				'SubwayStation',
				'Synagogue',
				'TaxiStand',
				'TrainStation',
				'Zoo',
			]
		);
	}

	static function getReviewNotice() {
		$html = null;
		$html = '<span>As of September, Google made a major change to review snippet schema and structure data markup. Google no longer support "self-serving" independent markup tied to the general types and has narrow support to specific types.</span><br><br> <span>You can read more about Google\'s change here:<br><a target="_blank" href="https://webmasters.googleblog.com/2019/09/making-review-rich-results-more-helpful.html">https://webmasters.googleblog.com/2019/09/making-review-rich-results-more-helpful.html</a></span><br><br>
        <span style="font-weight: bold">If you are a user of our plugin prior to September 2019, you need to remove the review schema for this tab on all pages and  post where you\'ve it for reviews and add back to the supported types (such as: book, course, event, movie, product, recipe, etc):</span><br><br>
        <span style="display: block;margin: 0 auto;max-width: 800px;">1. Simple uncheck the "enable" tab in this section<br>
        2. Update the page or post to remove the review schema.<br>
        3. Then re-add new review schema within the appropriet type tab(i.e. book, course, event, movie, product, recipe, etc)</span>
        <br>To review Google\'s documentation on <a target="_blank" href="https://developers.google.com/search/docs/data-types/review-snippet">https://developers.google.com/search/docs/data-types/review-snippet</a>';

		return $html;
	}
}
