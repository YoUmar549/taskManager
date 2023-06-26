import './bootstrap';
import axios from 'axios';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    var editableTasks = document.querySelectorAll('.editable-task');

    editableTasks.forEach(function(editableTask) {
        editableTask.addEventListener('dblclick', function() {
            this.contentEditable = true;
            this.focus();
        });

        editableTask.addEventListener('blur', function() {
            this.contentEditable = false;
            var updatedValue = this.innerText.trim();
            var taskId = this.getAttribute('data-task-id');
            var fieldName = this.getAttribute('data-field-name');

            // Send an AJAX request to update the task value
            axios.patch('/tasks/' + taskId, {
                field_name: fieldName,
                updated_value: updatedValue
            })
                .then(function(response) {
                    console.log(response.data);
                })
                .catch(function(error) {
                    console.error(error);
                });
        });
    });
});

