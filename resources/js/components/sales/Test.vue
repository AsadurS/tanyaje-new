
<script>
export default {
  data () {
    return {
      someVar: 'Test',
      templateRender: ''
    }
  },
  render: function(createElement) {
      if (this.templateRender)
      {
          return this.templateRender();
      }
  },
  props: {
    templateHtml: {
      templateHtml: true,
      type: String
    }
  },
  created () {
    this.$options.template = this.templateHtml
    this.templateRender = Vue.compile(this.templateHtml).render
  }
}
</script>