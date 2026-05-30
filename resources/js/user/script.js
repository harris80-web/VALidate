function goEdit() {
    document.getElementById("ap_profile").style.display = "none";
    document.getElementById("ap_editProfile").style.display = "block";
}

function goQuestion(questionNum) {
    const addButton = document.getElementById("sc_mainAddButton");
    addButton.style.display = "none";

    var i;
    var x = document.getElementsByClassName("question");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }

    document.getElementById("sc_main").style.display = "none";
    document.getElementById(questionNum).style.display = "block";
}

function openConsentForm() {
    const consentForm = document.getElementById("consent-form");
    consentForm.style.display = "flex";

}

// (New function) to show questions of a specific category
function showCategoryQuestions(categoryId) {
    // hide all question divs
    document.querySelectorAll('.question').forEach(el => el.classList.add('default-hidden'));

    // hide main category div
    document.getElementById('sc_main').classList.add('default-hidden');

    // show selected category
    document.getElementById(categoryId).classList.remove('default-hidden');

    // scroll to the questions
    document.getElementById(categoryId).scrollIntoView({ behavior: 'smooth' });
    // hide floating add button when viewing a category
    var addBtn = document.getElementById('sc_mainAddButton');
    if (addBtn) addBtn.style.display = 'none';
}

// Show main categories view and the floating add button
function showMainCategories() {
    document.querySelectorAll('.question').forEach(el => el.classList.add('default-hidden'));
    var scMain = document.getElementById('sc_main');
    if (scMain) scMain.classList.remove('default-hidden');
    var addBtn = document.getElementById('sc_mainAddButton');
    if (addBtn) addBtn.style.display = 'block';
    // scroll to top of categories
    if (scMain) scMain.scrollIntoView({ behavior: 'smooth' });
}

    window.openConsentForm = openConsentForm;
    window.goQuestion = goQuestion;
    window.goEdit = goEdit;
    window.showCategoryQuestions = showCategoryQuestions;


document.addEventListener('DOMContentLoaded', () => {
    // (New function) to enlarge selected radio button image
    const radiosDivs = document.querySelectorAll("div.sqd-button");

    const enlargeSelected = () => {
        radiosDivs.forEach(radioDiv => {
            const radio = radioDiv.querySelector("input");
            const span = radioDiv.querySelector("span");
            const img = radioDiv.querySelector("img");

            // Reset all first
            img.style.width = "80px";
            img.style.position = "relative";
            img.style.top = "0px";
            span.style.position = "relative";
            span.style.top = "0px";

            // Enlarge if checked
            if (radio.checked) {
                img.style.width = "120px";
                img.style.top = "-20px";
                span.style.top = "-20px";
            }
        });
    };

    // Run on page load
    enlargeSelected();

    // Add event listeners for all radios
    radiosDivs.forEach(radioDiv => {
        const radio = radioDiv.querySelector("input");
        radio.addEventListener("change", enlargeSelected);
    });

// const radios = document.querySelectorAll("div.sqd-button");

// radios.forEach(radio => {
//     const inputR = radio.querySelector("input");
//     const span = radio.querySelector("span");
//     const label = document.querySelector(`label[for="${inputR.id}"]`);


//     inputR.addEventListener("blur", function () {
//             label.querySelector("img").style.width = "85px";
//             label.querySelector("img").style.position = "relative";
//             label.querySelector("img").style.top = "0px";
//             span.style.position = "relative";
//             span.style.top = "0px";
//     });

//     inputR.addEventListener("focus", function(){
//             label.querySelector("img").style.width = "120px";
//             label.querySelector("img").style.position = "relative";
//             label.querySelector("img").style.top = "-20px";
//             span.style.position = "relative";
//             span.style.top = "-20px";

//     });
// });

    // (New function) for multi-step form submission with validation
    const surveySubmitBtn = document.getElementById("surveySubmit");
    const submitConfirmation = document.getElementById("submit-confirmation");
    const submitNoBtn = document.getElementById("submit-no");
    const suggestionForm = document.getElementById('suggestionForm');

    if (surveySubmitBtn && submitConfirmation && submitNoBtn && suggestionForm) {
        // Clear previous validation errors
        const clearErrors = () => {
            suggestionForm.querySelectorAll('.text-red-600').forEach(el => el.remove());
        };

        // Show validation errors below inputs
        const showErrors = (errors) => {
            Object.keys(errors).forEach(key => {
                const input = suggestionForm.querySelector(`[name="${key}"]`);
                if (input) {
                    const errorEl = document.createElement('p');
                    errorEl.classList.add('text-red-600', 'text-sm', 'mt-1');
                    errorEl.innerText = errors[key][0];
                    input.parentNode.appendChild(errorEl);
                }
            });
        };

        // Step 1: Click submit → validate before opening modal
        surveySubmitBtn.addEventListener('click', () => {
            clearErrors();
            const formData = new FormData(suggestionForm);

            axios.post('/survey/submit-suggestion', formData) // separate validation route
                .then(response => {
                    // validation passed → show modal
                    submitConfirmation.style.display = "flex";
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        showErrors(error.response.data.errors); // show inline errors
                    }
                });
        });

        // Step 2: Click No → hide modal
        submitNoBtn.addEventListener('click', () => {
            submitConfirmation.style.display = "none";
        });

        // Step 3: Click Yes → submit final form via Axios
        const modalSubmitBtn = submitConfirmation.querySelector('button[type="submit"]');
        if (modalSubmitBtn) {
            modalSubmitBtn.addEventListener('click', (e) => {
                e.preventDefault();
                clearErrors();

                const formData = new FormData(suggestionForm);

                axios.post('/survey/store', formData)
                    .then(response => {
                        window.location.href = '/survey/finished';
                    })
                    .catch(error => {
                        if (error.response && error.response.status === 422) {
                            showErrors(error.response.data.errors); // show errors if submission fails
                        }
                    });
            });
        }
    }
    // ensure floating add button visibility matches whether sc_main is visible
    var mainAddBtn = document.getElementById('sc_mainAddButton');
    var scMain = document.getElementById('sc_main');
    if (mainAddBtn) {
        try {
            if (scMain && !scMain.classList.contains('default-hidden')) mainAddBtn.style.display = 'block';
            else mainAddBtn.style.display = 'none';
        } catch (e) {
            mainAddBtn.style.display = 'none';
        }
    }
});

// Admin management JS
document.addEventListener('DOMContentLoaded', function () {
    var adminAddBtn = document.getElementById('adminAddButton');
    var adminModal = document.getElementById('adminModal');
    var adminForm = document.getElementById('adminForm');
    var adminCancelBtn = document.getElementById('adminCancelBtn');

    function openAdminModal(mode, admin) {
        if (!adminModal) return;
        adminModal.classList.remove('hidden');
        document.getElementById('adminModalTitle').textContent = mode === 'edit' ? 'Edit Admin' : 'Add Admin';
        document.getElementById('adminId').value = admin?.id || '';
        document.getElementById('adminName').value = admin?.name || '';
        document.getElementById('adminEmail').value = admin?.email || '';
        document.getElementById('adminPassword').value = '';
        document.getElementById('adminRole').value = admin?.role ?? '';
        document.getElementById('adminStatus').value = admin?.status ?? '';
    }

    function closeAdminModal() {
        if (!adminModal) return;
        adminModal.classList.add('hidden');
    }

    if (adminAddBtn) adminAddBtn.addEventListener('click', function () { openAdminModal('add'); });
    if (adminCancelBtn) adminCancelBtn.addEventListener('click', function () { closeAdminModal(); });

    // submit create/update
    if (adminForm) adminForm.addEventListener('submit', function (e) {
        e.preventDefault();
        var id = document.getElementById('adminId').value;
        var payload = {
            name: document.getElementById('adminName').value.trim(),
            email: document.getElementById('adminEmail').value.trim(),
            password: document.getElementById('adminPassword').value,
            role: document.getElementById('adminRole').value,
            status: document.getElementById('adminStatus').value,
        };

        var req;
        if (id) {
            req = axios.put('/admin/admins/' + id, payload);
        } else {
            req = axios.post('/admin/admins', payload);
        }

        req.then(function (res) { window.location.reload(); })
           .catch(function (err) {
               if (err.response && err.response.status === 422) {
                   alert('Validation error');
               } else {
                   alert('Failed to save admin');
               }
           });
    });

    // edit buttons
    document.body.addEventListener('click', function (e) {
        var btn = e.target.closest('.edit-admin-btn');
        if (!btn) return;
        var id = btn.dataset.adminId;
        if (!id) return;
        // fetch admin data from server or read from DOM — we'll request server
        axios.get('/admin/admins/' + id)
            .then(function (res) {
                openAdminModal('edit', res.data.admin);
            }).catch(function () {
                alert('Failed to load admin');
            });
    });

    // delete buttons
    document.body.addEventListener('click', function (e) {
        var btn = e.target.closest('.delete-admin-btn');
        if (!btn) return;
        var id = btn.dataset.adminId;
        if (!id) return;
        if (!confirm('Delete this admin?')) return;
        axios.delete('/admin/admins/' + id).then(function () { window.location.reload(); }).catch(function () { alert('Failed to delete admin'); });
    });

    // Admin search / filter live
    (function () {
        var searchInput = document.getElementById('um_search');
        var roleSelect = document.getElementById('um_role');
        var statusSelect = document.getElementById('um_status');
        var tbody = document.querySelector('table.w-full tbody');

        function normalize(s) { return (s || '').toString().trim().toLowerCase(); }

        function filterAdmins() {
            var q = normalize(searchInput ? searchInput.value : '');
            var role = normalize(roleSelect ? roleSelect.value : '');
            var status = normalize(statusSelect ? statusSelect.value : '');
            if (!tbody) return;
            Array.from(tbody.querySelectorAll('tr')).forEach(function (tr) {
                var tds = tr.querySelectorAll('td');
                if (tds.length < 5) { tr.style.display = ''; return; }
                var name = normalize(tds[1].textContent);
                var email = normalize(tds[2].textContent);
                var roleText = normalize(tds[3].textContent);
                var statusText = normalize(tds[4].textContent);

                var matchesQ = !q || name.indexOf(q) !== -1 || email.indexOf(q) !== -1 || roleText.indexOf(q) !== -1 || statusText.indexOf(q) !== -1;
                var matchesRole = !role || roleText === role;
                var matchesStatus = !status || statusText === status;

                tr.style.display = (matchesQ && matchesRole && matchesStatus) ? '' : 'none';
            });
        }

        if (searchInput) searchInput.addEventListener('input', filterAdmins);
        if (roleSelect) roleSelect.addEventListener('change', filterAdmins);
        if (statusSelect) statusSelect.addEventListener('change', filterAdmins);
    })();
});
// ---------- Survey modal: create question (AJAX) ----------
document.addEventListener('DOMContentLoaded', function () {
    // ensure axios has CSRF token if meta exists
    var tokenMeta = document.querySelector('meta[name="csrf-token"]');
    if (tokenMeta) axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');

    function clearModalErrors() {
        var form = document.getElementById('surveyCreationForm');
        if (!form) return;
        form.querySelectorAll('.text-red-600').forEach(el => el.remove());
    }

    function showModalErrors(errors) {
        var form = document.getElementById('surveyCreationForm');
        if (!form) return;
        Object.keys(errors).forEach(function (key) {
            var field;
            if (key === 'options' || key.startsWith('options')) {
                field = form.querySelector('#optionsContainer');
            } else {
                field = form.querySelector('#' + key) || form.querySelector(`[name="${key}"]`);
            }
            if (field) {
                var p = document.createElement('p');
                p.className = 'text-red-600 text-sm mt-1';
                p.innerText = Array.isArray(errors[key]) ? errors[key][0] : errors[key];
                field.parentNode.appendChild(p);
            }
        });
    }

    // Wire add buttons
    document.querySelectorAll('.add-question-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var modal = document.getElementById('surveyCreation');
            if (modal) modal.dataset.category = btn.dataset.category;
            var m = document.getElementById('surveyCreation');
            if (m) m.classList.remove('hidden');
            var nameInput = document.getElementById('newQuestionName'); if (nameInput) nameInput.focus();
        });
    });

    var mainAdd = document.getElementById('sc_mainAddButton');
    if (mainAdd) mainAdd.addEventListener('click', function () {
        // open category creation modal instead of question modal
        var m = document.getElementById('categoryCreation');
        if (m) { m.classList.remove('hidden'); }
        var name = document.getElementById('newCategoryName'); if (name) name.focus();
    });

    // Edit question inline (robust detection)
    document.body.addEventListener('click', function (e) {
        var target = e.target;
        var img = target.closest('img');
        var btn = target.closest('[data-action="edit"]') || target.closest('.edit-question-btn') || target.closest('button');

        // If an image was clicked and its filename contains "edit", treat it as edit
        if (img && img.getAttribute('src') && img.getAttribute('src').toLowerCase().includes('edit')) {
            var card = img.closest('.bg-white');
            if (card) startEditCard(card);
            return;
        }

        // If an element explicitly marked as edit was clicked
        if (btn && (btn.dataset && btn.dataset.action === 'edit' || btn.classList.contains('edit-question-btn'))) {
            var card2 = btn.closest('.bg-white');
            if (card2) startEditCard(card2);
            return;
        }
    });

    function startEditCard(card) {
        if (card.dataset.editing === '1') return;
        card.dataset.editing = '1';
        // store original html to revert on cancel
        card.dataset.original = card.innerHTML;

        var titleEl = card.querySelector('h3');
        var titleText = titleEl ? titleEl.textContent : '';
        var titleInput = document.createElement('input');
        titleInput.type = 'text'; titleInput.value = titleText; titleInput.className = 'w-full px-2 py-1 border rounded';
        if (titleEl) titleEl.replaceWith(titleInput);

        // convert options to editable rows
        var optionRows = [];
        var optionNodes = Array.from(card.querySelectorAll('div.flex.gap-2.font-light'));
        optionNodes.forEach(function (optNode) {
            var label = optNode.querySelector('label');
            var input = document.createElement('input');
            input.type = 'text'; input.value = label ? label.textContent : '';
            input.className = 'option-input w-full px-2 py-1 rounded';
            // if radio exists and value corresponds to option id, try to read id from data attribute if present
            var wrapper = document.createElement('div'); wrapper.className = 'flex gap-2 option-row edit-row';
            // try to attach existing option id via data-option-id attribute on label
            var optId = optNode.dataset ? optNode.dataset.optionId : undefined;
            if (!optId) {
                // sometimes not present; try to parse from radio value attribute? we don't have id there
            }
            if (optId) input.dataset.optionId = optId;
            var rem = document.createElement('button'); rem.type = 'button'; rem.className = 'px-3 py-1 bg-red-100 text-red-700 rounded remove-option-btn'; rem.textContent = 'Remove';
            wrapper.appendChild(input); wrapper.appendChild(rem);
            // wire remove immediately so it always works
            rem.addEventListener('click', function () { wrapper.remove(); });
            optNode.replaceWith(wrapper);
            optionRows.push(wrapper);
        });

        // add an area for adding options and a save/cancel row
        // classes may include Tailwind bracketed names, so search by text content if necessary
        var addOptsDiv = Array.from(card.querySelectorAll('*')).find(function (el) {
            return el.textContent && el.textContent.trim().includes('+ ADD OPTIONS');
        });
        var addArea = document.createElement('div');
        addArea.className = 'mt-3';
        var addLink = document.createElement('button'); addLink.type = 'button'; addLink.className = 'text-blue-600 underline add-option-edit'; addLink.textContent = '+ ADD OPTIONS';
        addArea.appendChild(addLink);
        if (addOptsDiv) addOptsDiv.replaceWith(addArea); else card.appendChild(addArea);

        addLink.addEventListener('click', function () {
            var container = card;
            var row = document.createElement('div'); row.className = 'flex gap-2 option-row edit-row';
            var inp = document.createElement('input'); inp.type = 'text'; inp.className = 'option-input w-full px-2 py-1 rounded'; inp.placeholder = 'Option';
            var rem = document.createElement('button'); rem.type = 'button'; rem.className = 'px-3 py-1 bg-red-100 text-red-700 rounded remove-option-btn'; rem.textContent = 'Remove';
            row.appendChild(inp); row.appendChild(rem);
            // insert before the controls footer if present
            var footer = card.querySelector('.flex.justify-end');
            if (footer) footer.before(row); else card.appendChild(row);

            rem.addEventListener('click', function () { row.remove(); });
        });

        // wire remove existing option buttons
        card.querySelectorAll('.remove-option-btn').forEach(btn => btn.addEventListener('click', function(){ btn.closest('.edit-row').remove(); }));

        // add save / cancel buttons
        var controls = document.createElement('div'); controls.className = 'flex gap-2 mt-3';
        var saveBtn = document.createElement('button'); saveBtn.type = 'button'; saveBtn.className = 'px-3 py-1 bg-green-600 text-white rounded'; saveBtn.textContent = 'Save';
        var cancelBtn = document.createElement('button'); cancelBtn.type = 'button'; cancelBtn.className = 'px-3 py-1 bg-gray-300 rounded'; cancelBtn.textContent = 'Cancel';
        controls.appendChild(cancelBtn); controls.appendChild(saveBtn);
        // append to footer or end
        var footer = card.querySelector('.flex.justify-end');
        if (footer) footer.after(controls); else card.appendChild(controls);

        cancelBtn.addEventListener('click', function () {
            // revert
            card.innerHTML = card.dataset.original;
            delete card.dataset.editing; delete card.dataset.original;
        });

        saveBtn.addEventListener('click', function () {
            // gather data
            var qid = card.querySelector('.delete-question-btn') ? card.querySelector('.delete-question-btn').dataset.questionId : null;
            if (!qid) { alert('Missing question id'); return; }
            var newName = card.querySelector('input[type="text"]').value.trim();
            var optionInputs = Array.from(card.querySelectorAll('.edit-row .option-input'));
            var options = optionInputs.map(function (inp) { return { id: inp.dataset.optionId || null, description: inp.value.trim() }; }).filter(o => o.description.length);
            // detect removed option ids by comparing original options
            var originalOptionIds = Array.from((card.dataset.original || '').matchAll(/name="question_\d+" value="(\d+)"/g)).map(m=>m[1]);
            // simpler: collect current option ids
            var currentIds = optionInputs.map(i=>i.dataset.optionId).filter(Boolean).map(String);
            var deleted = [];
            // if originalOptionIds found, compute difference
            if (originalOptionIds.length) {
                originalOptionIds.forEach(function (oid) { if (oid && !currentIds.includes(oid)) deleted.push(oid); });
            }

            axios.put('/admin/surveyContent/questions/' + qid, {
                name: newName,
                options: options,
                deleted_option_ids: deleted
            }).then(function(res){
                // replace card with updated HTML built from response
                var q = res.data.question;
                var newCardHtml = '<div class="flex justify-between font-bold">' +
                    '<h3>' + escapeHtml(q.name) + '</h3>' +
                    '<div class="flex gap-3">' +
                    '<img src="' + document.getElementById('surveyCreation')?.dataset.editSrc + '" alt="">' +
                    '<button type="button" class="delete-question-btn" data-question-id="' + q.id + '" onclick="showDeleteConfirmation(\'question\', \'" + q.id + "\')">' +
                    '<img src="' + document.getElementById('surveyCreation')?.dataset.delSrc + '" alt="">' +
                    '</button></div></div>';
                if (q.options && q.options.length) {
                    newCardHtml += '<h5 class="font-regular mt-3">Options:</h5>';
                    q.options.forEach(function(opt){
                        newCardHtml += '<div class="flex gap-2 font-light">' +
                            '<input type="radio" name="question_' + q.id + '" value="' + opt.id + '">' +
                            '<label>' + escapeHtml(opt.description) + '</label>' +
                            '</div>';
                    });
                    newCardHtml += '<div class="text-[#010767] text-[13px] font-bold mt-5"><h4>+ ADD OPTIONS</h4></div>';
                }
                card.innerHTML = newCardHtml;
                delete card.dataset.editing; delete card.dataset.original;
            }).catch(function(err){
                if (err.response && err.response.status === 422) {
                    alert('Validation error');
                } else {
                    alert('Failed to save');
                }
            });
        });
    }

    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/\"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    var addOptionBtn = document.getElementById('addOptionBtn');
    if (addOptionBtn) addOptionBtn.addEventListener('click', function () {
        var container = document.getElementById('optionsContainer');
        var idx = container.querySelectorAll('.option-row').length + 1;
        var row = document.createElement('div');
        row.className = 'flex gap-2 option-row';
        var inp = document.createElement('input');
        inp.type = 'text'; inp.className = 'option-input w-full px-2 py-1 rounded'; inp.placeholder = 'Option ' + idx;
        var rem = document.createElement('button'); rem.type = 'button'; rem.className = 'px-3 py-1 bg-white text-[#010767] rounded'; rem.textContent = 'Remove';
        rem.addEventListener('click', function () { row.remove(); });
        row.appendChild(inp); row.appendChild(rem); container.appendChild(row);
    });

    var form = document.getElementById('surveyCreationForm');
    if (!form) return;
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearModalErrors();
        var name = document.getElementById('newQuestionName').value.trim();
        if (!name) { showModalErrors({ name: ['Please enter a question name'] }); return; }

        var options = Array.from(document.querySelectorAll('#optionsContainer .option-input'))
            .map(function (i) { return i.value.trim(); }).filter(Boolean);

        var categoryId = document.getElementById('surveyCreation').dataset.category || '';

        axios.post('/admin/surveyContent/questions', {
            category_id: categoryId,
            name: name,
            options: options
        }).then(function (res) {
            var q = res.data.question;
            // append card to DOM
            var card = document.createElement('div');
            card.className = 'bg-white w-full px-6 py-3 rounded-md flex flex-col justify-between text-[20px] text-[#010767] font-medium shadow-md/20 mb-4';
            var top = document.createElement('div'); top.className = 'flex justify-between font-bold';
            var h3 = document.createElement('h3'); h3.textContent = q.name; top.appendChild(h3);
            var controls = document.createElement('div'); controls.className = 'flex gap-3';
            var editImg = document.createElement('img'); editImg.src = document.getElementById('surveyCreation')?.dataset.editSrc || '';
            var delImg = document.createElement('img'); delImg.src = document.getElementById('surveyCreation')?.dataset.delSrc || '';
            controls.appendChild(editImg);
            var delBtn = document.createElement('button'); delBtn.type = 'button'; delBtn.className = 'delete-question-btn'; delBtn.dataset.questionId = q.id;
            delBtn.appendChild(delImg);
            controls.appendChild(delBtn);
            top.appendChild(controls);
            card.appendChild(top);
            if (q.options && q.options.length) {
                var h5 = document.createElement('h5'); h5.className = 'font-regular mt-3'; h5.textContent = 'Options:'; card.appendChild(h5);
                var radioName = 'question_' + q.id;
                q.options.forEach(function (opt) {
                    var row = document.createElement('div'); row.className = 'flex gap-2 font-light';
                    var radio = document.createElement('input'); radio.type = 'radio'; radio.name = radioName; radio.value = opt.id;
                    var label = document.createElement('label'); label.textContent = opt.description;
                    row.appendChild(radio); row.appendChild(label); card.appendChild(row);
                });
                var addOptsDiv = document.createElement('div'); addOptsDiv.className = 'text-[#010767] text-[13px] font-bold mt-5'; addOptsDiv.innerHTML = '<h4>+ ADD OPTIONS</h4>';
                card.appendChild(addOptsDiv);
            }

            if (categoryId) {
                var container = document.getElementById('category_' + categoryId);
                if (container) {
                    var footer = container.querySelector('.flex.justify-end');
                    if (footer) container.insertBefore(card, footer);
                    else container.appendChild(card);
                }
            } else {
                document.getElementById('sc_main').appendChild(card);
            }

            // close modal and reset
            document.getElementById('surveyCreation').classList.add('hidden');
            form.reset();
            var rows = document.querySelectorAll('#optionsContainer .option-row');
            rows.forEach(function (r, i) { if (i > 0) r.remove(); else r.querySelector('.option-input').value = ''; });
        }).catch(function (err) {
            if (err.response && err.response.status === 422) {
                showModalErrors(err.response.data.errors || {});
            } else {
                alert('Failed to save question.');
            }
        });
    });
    // Expose helper to be callable from inline attributes
    window.goCancelAdding = function () {
        var modal = document.getElementById('surveyCreation'); if (!modal) return;
        modal.classList.add('hidden'); modal.dataset.category = '';
        var form = document.getElementById('surveyCreationForm'); if (form) form.reset();
        var rows = document.querySelectorAll('#optionsContainer .option-row');
        rows.forEach(function (r, i) { if (i > 0) r.remove(); else r.querySelector('.option-input').value = ''; });
    };

    window.showSurveyCreation = function (categoryId) {
        var modal = document.getElementById('surveyCreation'); if (!modal) return;
        modal.dataset.category = categoryId || '';
        modal.classList.remove('hidden');
        var name = document.getElementById('newQuestionName'); if (name) name.focus();
    };
    
    // Category creation handlers
    function clearCategoryErrors() {
        var form = document.getElementById('categoryCreationForm'); if (!form) return;
        form.querySelectorAll('.text-red-600').forEach(el => el.remove());
    }

    function showCategoryErrors(errors) {
        var form = document.getElementById('categoryCreationForm'); if (!form) return;
        Object.keys(errors).forEach(function (key) {
            var field = form.querySelector('#' + key) || form.querySelector(`[name="${key}"]`);
            if (field) {
                var p = document.createElement('p'); p.className = 'text-red-600 text-sm mt-1'; p.innerText = Array.isArray(errors[key]) ? errors[key][0] : errors[key];
                field.parentNode.appendChild(p);
            }
        });
    }

    var catForm = document.getElementById('categoryCreationForm');
    if (catForm) {
        catForm.addEventListener('submit', function (e) {
            e.preventDefault(); clearCategoryErrors();
            var name = document.getElementById('newCategoryName').value.trim();
            var type = document.getElementById('newCategoryType').value;
            if (!name) { showCategoryErrors({ newCategoryName: ['Please enter a category name'] }); return; }

            axios.post('/admin/surveyContent/categories', { name: name, type: type })
                .then(function (res) {
                    // saved on server — refresh to show persisted category
                    window.location.reload();
                })
                .catch(function (err) {
                    if (err.response && err.response.status === 422) showCategoryErrors(err.response.data.errors || {});
                    else {
                        // if endpoint missing or error, still append locally
                        var container = document.getElementById('sc_main');
                        if (container) {
                            var card = document.createElement('div');
                            card.className = 'bg-[#010767] w-full px-6 py-3 rounded-md flex justify-between text-white font-normal shadow-md/20 mb-4';
                            var h3 = document.createElement('h3'); h3.textContent = name; card.appendChild(h3);
                            container.appendChild(card);
                        }
                        var modal = document.getElementById('categoryCreation'); if (modal) modal.classList.add('hidden');
                        catForm.reset();
                    }
                });
        });

        window.goCancelCategory = function () {
            var modal = document.getElementById('categoryCreation'); if (!modal) return; modal.classList.add('hidden');
            var f = document.getElementById('categoryCreationForm'); if (f) f.reset(); clearCategoryErrors();
        };
    }
});

// Delete question flow: open confirmation modal and call DELETE
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('surveyDeleteConfirmation');
    if (!deleteModal) return; // nothing to wire

    // ensure modal is direct child of body so it's not clipped/overlapped
    if (deleteModal.parentNode !== document.body) document.body.appendChild(deleteModal);

    // Use modal dataset to store current question id; expose helpers like showSurveyCreation
    document.body.addEventListener('click', function (e) {
        var btn = e.target.closest('.delete-question-btn');
        if (btn) {
            // prefer inline call, but support delegated clicks too
            var qid = btn.dataset.questionId;
            if (window.showDeleteConfirmation) window.showDeleteConfirmation('question', qid);
        }
    });

    window.showDeleteConfirmation = function (type, id) {
        if (!deleteModal) return;
        deleteModal.dataset.deleteType = type;
        deleteModal.dataset.deleteId = id;
        deleteModal.classList.remove('hidden');
    };

    window.goCancelDelete = function () {
        if (!deleteModal) return;
        deleteModal.dataset.deleteType = '';
        deleteModal.dataset.deleteId = '';
        deleteModal.classList.add('hidden');
    };

    var cancelBtn = document.getElementById('deleteCancelBtn');
    var confirmBtn = document.getElementById('deleteConfirmBtn');
    if (cancelBtn) cancelBtn.addEventListener('click', function () { window.goCancelDelete(); });
    if (confirmBtn) confirmBtn.addEventListener('click', function () {
        var dtype = deleteModal.dataset.deleteType;
        var id = deleteModal.dataset.deleteId;
        if (!dtype || !id) return;

        if (dtype === 'question') {
            axios.delete('/admin/surveyContent/questions/' + id)
                .then(function (res) {
                    var btn = document.querySelector('.delete-question-btn[data-question-id="' + id + '"]');
                    if (btn) {
                        var card = btn.closest('.bg-white');
                        if (card) card.remove();
                    }
                    window.goCancelDelete();
                })
                .catch(function (err) {
                    alert('Failed to delete question.');
                    window.goCancelDelete();
                });
            return;
        }

        if (dtype === 'category') {
            axios.delete('/admin/surveyContent/categories/' + id)
                .then(function (res) {
                    // refresh page so categories and questions update from server
                    window.location.reload();
                })
                .catch(function (err) {
                    alert(err.response && err.response.data && err.response.data.message ? err.response.data.message : 'Failed to delete category.');
                    window.goCancelDelete();
                });
            return;
        }
    });
});
// const radioDiv = document.querySelectorAll("div.sqd-button");

// radioDiv.forEach(rDiv => {
//     const radio = rDiv.querySelector("input");
//     radio.addEventListener("change",function(){
//         const span = rDiv.querySelector("span");
//         const label = rDiv.querySelector(`label[for="${radio.id}"]`);



//         if(radio.checked){
//             label.querySelector("img").style.width = "120px";
//             label.querySelector("img").style.position = "relative";
//             label.querySelector("img").style.top = "-20px";
//             span.style.position = "relative";
//             span.style.top = "-20px";
//         } else {
//             label.querySelector("img").style.width = "85px";
//             label.querySelector("img").style.position = "relative";
//             label.querySelector("img").style.top = "0px";
//             span.style.position = "relative";
//             span.style.top = "0px";
//         }

//     });
// });
