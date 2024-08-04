<template>
  <div v-if="formattedJson !== 'null'">
    <div class="code-editor-container">
      <div class="code-editor">
        <pre ref="editor" contenteditable="true" spellcheck="false">{{ formattedJson }}</pre>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    jsonData: {
      type: Object,
      required: true
    }
  },
  computed: {
    formattedJson() {
      return JSON.stringify(this.jsonData, null, 2);
    }
  },
  mounted() {
  },
  methods: {
    
  }
};
</script>

<style scoped>
.code-editor-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 50vh;
  background-color: #2e3440; /* Dark background */
  padding: 20px;
}

.code-editor {
  display: flex;
  position: relative;
  width: 90%;
  height: 90%;
  background-color: #2e3440; /* Same as container background */
  border: 1px solid #4c566a; /* Border for the editor */
  border-radius: 5px;
  overflow: hidden;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.line-numbers {
  counter-reset: line;
  flex-shrink: 0;
  background: #3b4252; /* Darker background for line numbers */
  color: #d8dee9; /* Light text color */
  padding: 10px;
  text-align: right;
  user-select: none;
}

.line-numbers span {
  display: block;
  line-height: 1.5;
}

/* Styling to align line numbers and code */
.code-editor pre {
  flex-grow: 1;
  padding-left: 10px;
  margin: 0;
  font-family: 'Fira Code', monospace; /* Monospaced font */
  font-size: 14px;
  line-height: 1.5;
  color: #d8dee9; /* Light text color */
  background-color: #2e3440; /* Dark background */
  outline: none;
  overflow: auto;
  white-space: pre-wrap; /* Preserve whitespace and line breaks */
  word-wrap: break-word;
}

/* Add a custom scrollbar */
.code-editor pre::-webkit-scrollbar {
  width: 10px;
}

.code-editor pre::-webkit-scrollbar-thumb {
  background-color: #4c566a;
  border-radius: 5px;
}
</style>
