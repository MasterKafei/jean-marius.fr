import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import Image from '@editorjs/image';

let editorjsToolsScoped = [];
if (typeof editorjsTools !== 'undefined') {
  // eslint-disable-next-line no-undef
  editorjsToolsScoped = editorjsTools;
}

editorjsToolsScoped.Header = Header;
editorjsToolsScoped.List = List;
editorjsToolsScoped.Image = Image;

let editorjsConfigsScoped = [];
if (typeof editorjsConfigs !== 'undefined') {
  editorjsConfigsScoped = editorjsConfigs;
}

const editors = [];

async function editorjsSave(holderId) {
  const editorHolder = document.getElementById(holderId);
  const editorInput = document.getElementById(editorHolder.getAttribute('data-input-id'));
  const editor = editors[holderId];

  const savePromise = editor.save().then((outputData) => {
    editorInput.value = JSON.stringify(outputData);
  });

  await savePromise;
}

if (typeof editorjsConfigsScoped !== 'undefined') {
  editorjsConfigsScoped.forEach((config) => {
    if (typeof config.tools !== 'undefined') {
     Object.keys(config.tools).forEach((toolName) => {
        config.tools[toolName].class = editorjsToolsScoped[config.tools[toolName].className];
      });
    }

    // eslint-disable-next-line no-param-reassign,func-names
    config.onChange = async function () {
      await editorjsSave(this.holder);
    };

    const editorHolder = document.getElementById(config.holder);
    const editorForm = editorHolder.closest('form');

    if (!editorForm.hasEditorjsListener) {
      editorForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const form = event.target;
        form.hasEditorjsListener = true;

        // save all editors in form
        const editorHolders = form.querySelectorAll('.editorjs-holder');
        const savePromises = [];
        editorHolders.forEach((holder) => {
          savePromises.push(editorjsSave(holder.id));
        });

        Promise.all(savePromises).then(() => {
          form.submit();
        });
      });
    }

    if (config.name === "read_only") {
        config.readOnly = true;
    }

    editors[config.holder] = new EditorJS(config);
  });
}