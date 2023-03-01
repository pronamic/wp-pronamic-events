/**
 * Edit event start date.
 * 
 * @link https://github.com/WordPress/gutenberg/blob/3da717b8d0ac7d7821fc6d0475695ccf3ae2829f/packages/create-block-tutorial-template/templates/src/edit.js.mustache
 * @link https://github.com/WordPress/gutenberg/tree/9385d9c0c4a7a373e2c757d06bd71f8d989d6167/packages/block-editor/src/components/inspector-controls
 * @link https://github.com/WordPress/gutenberg/blob/9385d9c0c4a7a373e2c757d06bd71f8d989d6167/packages/block-library/src/post-date/block.json
 */

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';

/**
 * WordPress components that create the necessary UI elements for the block
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-components/
 */
import { TextControl, PanelBody } from '@wordpress/components';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	let content = __( 'Event Start Date', 'pronamic-events' );

	return (
		<>
			<InspectorControls>
				<PanelBody>
					<TextControl
						label={ __( 'Format', 'pronamic-events' ) }
						value={ attributes.format }
						onChange={ ( val ) => setAttributes( { format: val } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div {...useBlockProps()}>❴ { content } ❵</div>
		</>
	);
}
