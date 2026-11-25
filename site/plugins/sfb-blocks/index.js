// Panel preview of the block "Aufklappbereich": the configured title instead of the block name,
// "Titel" as long as no title is set
panel.plugin('sfb/blocks', {
  blocks: {
    accordion: {
      inheritAttrs: false,
      emits: ['open'],
      props: {
        content: {
          type: Object,
          default: () => ({})
        },
        fieldset: {
          type: Object,
          default: () => ({})
        }
      },
      computed: {
        titleFieldset() {
          const title = String(this.content.title ?? '').trim();
          return { ...this.fieldset, name: title !== '' ? title : 'Titel', label: '' };
        }
      },
      template: `
        <k-block-title
          :class="$attrs.class"
          :content="content"
          :fieldset="titleFieldset"
          :style="$attrs.style"
          @dblclick="$emit('open')"
        />
      `
    }
  }
});
