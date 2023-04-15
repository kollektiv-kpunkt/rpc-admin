<a href="#" class=" ml-auto rpc-button orange mt-1 text-center w-fit" id="rpc-custom-field-creator" onclick="addField()">{{__("Neues Feld")}}</a>

<script>

    function addField() {
        let e = window.event;
        e.preventDefault();
        let input = document.createElement("input");
        input.type = "text";
        input.name = "";
        input.placeholder = "{{__("Feldname")}}";
        input.required = true;
        input.autofocus = true;
        input.classList.add("block", "mt-1", "w-full");
        e.target.parentNode.insertBefore(input, e.target);
        e.target.innerHTML = "{{__("Feld hinzufügen")}}";
        e.target.onclick = function (e){
            createField(e, input)
        };
    }

    function createField(e, input) {
        e.preventDefault();
        if (input.value === "") {
            alert("{{__("Bitte einen Namen für das Feld eingeben")}}");
            return;
        }
        let name = input.value.toLowerCase();
        let markup = `
        <div>
            <label class="block font-medium text-sm text-gray-700" for="data[${name}]">
            Benutzerdefiniertes Feld: ${name}
            </label>
            <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" id="data[${name}]" type="text" name="data[${name}]" autofocus="autofocus">
        </div>
        `;
        input.parentNode.insertBefore(document.createRange().createContextualFragment(markup), input);
        input.remove();
        let fieldCreatorButton = document.getElementById("rpc-custom-field-creator");
        fieldCreatorButton.innerHTML = "{{__("Neues Feld")}}";
        fieldCreatorButton.onclick = function (){
            addField()
        };
    }

</script>
