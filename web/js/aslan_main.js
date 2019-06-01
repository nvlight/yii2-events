// Показываем модалку
const modalOpen = document.querySelector('.call-modal');
const modalOverlay = document.querySelector('.overlay');
const modalClose = document.querySelector('.modal__exit');

modalOpen.addEventListener('click', function () {
    modalOverlay.style.display = 'flex';
})
modalClose.addEventListener('click', function () {
    modalOverlay.style.display = 'none';
})
window.addEventListener('click', function (event) {
    if (event.target == modalOverlay) modalOverlay.style.display = 'none';
})
window.addEventListener('keydown', function (event) {
    if (event.key === "Escape") modalOverlay.style.display = 'none';
})

const addFamily = document.querySelector('.add-family__btn');
const list = document.querySelector('.modal-list');


// Событие создаем структуру
function createElements(e) {
    e.preventDefault();
    // Добавляем разметку
    const li = document.createElement('li');
    li.innerHTML = `
		<div class="modal-list__header">
			<div class="user-data">

			</div>


			<div class="modal-list__edit">
				<a href="#" class="modal-list__btn modal-list__btn_edit">редактировать</a>
				<a href="#" class="modal-list__btn modal-list__btn_remove">удалить</a>
			</div>
		</div>

		<div class="modal-list__body">

		<div class="list-form">

			<div class="list-form__group">
				<p class="modal-form__field">
					<label for="family_name" class="modal-form__label">Имя<span class="modal-form__req">*</span></label>
					<input type="text" name="family_name" id="family_name" class="modal-form__input">
				</p>
				<p class="modal-form__field">
					<label for="family_surename" class="modal-form__label">Фамилия<span class="modal-form__req">*</span></label>
					<input type="text" name="family_surename" id="family_surename" class="modal-form__input">
				</p>
			</div>

			<div class="list-form__group">
				<p class="modal-form__field">
					<label for="family_date" class="modal-form__label">Дата рождения<span class="modal-form__req">*</span></label>
					<input type="date" name="family_date" id="family_date" placeholder="ДД/ММ/ГГГГ" class="modal-form__input">
				</p>
				<p class="modal-form__field modal-form__field_selected">
					<label for="family_select" class="modal-form__label">Укажите степень родства<span class="modal-form__req">*</span></label>
					<select name="family_select" id="family_select" class="modal-form__input modal-form__input_select">
						<option value="son">Сын</option>
						<option value="husbend">Муж</option>
						<option value="wife">Жена</option>
						<option value="grandfather">Дедушка</option>
						<option value="grandmother">Бабушка</option>
					</select>
				</p>
			</div>

		</div>

		<div class="modal-list__edit list-edit">
			<button class="list-edit__btn list-edit__btn_save">Сохранить</button>
			<button class="list-edit__btn list-edit__btn_remove">Удалить</button>
		</div>

		</div>`;
    // Добавялем в блок списка элемент списка
    list.appendChild(li);

    // Сохраняем блок
    let btn = document.querySelector('.list-edit__btn_save');
    btn.addEventListener('click', function (event) {
        event.preventDefault();
        //
        if (document.querySelector('#family_name').value === ''|| document.querySelector('#family_surename').value==='' || document.querySelector('#family_date').value === '') {
            alert('Пожалуйста заполните все поля')
        } else {
            document.querySelector('.user-data').innerHTML = `
				<span class="modal-list__relative">
					${document.querySelector('#family_select').selectedOptions[0].label}</span>
				<span class="modal-list__name">
					${document.querySelector('#family_name').value}
					${document.querySelector('#family_surename').value}
				</span>
				`;
            document.querySelector('.modal-list__body').style.display = 'none';
        }

    });
    //  Показываем
    document.querySelector('.modal-list__btn_edit').addEventListener('click', function (event) {
        event.preventDefault();
        document.querySelector('.modal-list__body').style.display = 'flex';
    });
    //  Удаляем
    document.querySelector('.modal-list__btn_remove').addEventListener('click', function () {
        this.parentElement.parentElement.parentElement.remove();
    });



}

addFamily.addEventListener('click', createElements)
