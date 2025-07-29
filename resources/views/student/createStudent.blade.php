@extends('layout.app')

@section('css')
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .remove-btn {
            color: red;
            cursor: pointer;
            margin-left: 10px;
        }
    </style>
@endsection
@section('content')


    <h2>
        @if (!is_null($structure))
            {{ $structure->form_name }}
        @else
            Dynamic Form Builder
        @endif
    </h2>
    <form id="dynamicForm" action="{{ route('createStudent') }}">
        @csrf
        <div id="formFields">
            <!-- Dynamic fields will be added here -->
            @if (!is_null($structure) && isset($structure->fields))
                @foreach ($structure->fields as $key)
                    @php
                        $fieldName = ucwords(str_replace('_', ' ', $key));
                    @endphp
                    <div class="form-group">
                        <label for="{{ $key }}">{{ $fieldName }} : </label>
                        <input type="text" name="{{ $key }}" id="{{ $key }}" />
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" onclick="addField()">➕ Add Field</button>
        <button type="submit">Submit</button>
    </form>
@endsection
@section('javascripts')
    <script>
        let fieldIndex = 0;

        function addField() {
            fieldIndex++;
            const fieldContainer = document.createElement("div");
            fieldContainer.className = "form-group";
            let fieldName = window.prompt("Enter field name:");
            let key = fieldName.replace(" ", "_").toLowerCase();
            fieldContainer.innerHTML = `
                <label for="field_${fieldIndex}">${fieldName}: </label>
                <input type="text" id="field_${fieldIndex}" name="${key}" placeholder="Enter value">
                <span class="remove-btn" onclick="removeField(this)">&times;</span>
            `;
            document.getElementById("formFields").appendChild(fieldContainer);
        }

        function removeField(element) {
            element.parentElement.remove();
        }

        document.getElementById("dynamicForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent page reload

            // Collect form data
            const formData = {};
            document.querySelectorAll("#formFields input").forEach((input, index) => {
                formData[input.name] = input.value;
            });
            formData['_token'] = "{{ csrf_token() }}";
            let url = document.getElementById("dynamicForm").action;
            // Send data to the server (Laravel)
            fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => alert(data.message))
                .catch(error => console.error("Error:", error));
        });
    </script>
@endsection
</body>

</html>
