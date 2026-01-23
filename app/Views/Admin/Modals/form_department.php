<form method="POST" id="formDepartment" data-url="<?= getURL('admin/departments/add') ?>">
    <input type="hidden" name="ids" value="<?= ($formType == 'edit') ? $id : null ?>">
    <input type="hidden" name="formType" value="<?= $formType ?>">
    <div class="p-6">
        <div class="space-y-4">
            <div>
                <label class="block text-gray-600 text-sm mb-2">Department Name</label>
                <input type="text" id="departmentName" name="department_name"
                    class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary"
                    placeholder="e.g., IT Support">
            </div>

            <div>
                <label class="block text-gray-600 text-sm mb-2">Description</label>
                <textarea id="departmentDescription" name="department_description"
                    class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary"
                    rows="3" placeholder="Describe the department's purpose and responsibilities"></textarea>
            </div>

            <div>
                <label class="block text-gray-600 text-sm mb-2">Status</label>
                <select id="departmentStatus" name="department_status"
                    class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-600 text-sm mb-2">Department Head</label>
                <input type="text" id="departmentHead" name="department_head"
                    class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-secondary"
                    placeholder="Optional">
            </div>

            <div>
                <label class="block text-gray-600 text-sm mb-2">Default Ticket Categories</label>
                <div id="categoriesContainer" class="space-y-2 grid grid-cols-5 gap-2">
                    <?php foreach($categories as $ct): ?>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="arr_categories[]" id="ct<?= $ct['category_id'] ?>" value="<?= $ct['category_id'] ?>">
                            <label for="ct<?= $ct['category_id'] ?>"><?= $ct['category_name'] ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    
</form>

<script>
    var elements = {
        form: $('#formDepartment'),
        btnSubmit: $('#btnSubmit'),
    }

    elements.btnSubmit.click(function(e) {
        e.preventDefault();
        elements.form.trigger('submit');
    });

    elements.form.submit(function(e) {
        e.preventDefault();
        var formData = elements.form.serialize();
        $.ajax({
            url: elements.form.attr('data-url'),
            method: 'POST',
            dataType: 'json',
            data: formData,
            success: function(res) {
                if (res.success) {
                    elements.form[0].reset();
                    elements.form.find('select').val('');
                    $('#departmentsTable').DataTable().ajax.reload();
                    close_modal('modaldetail');
                    showToast(res.msg, 'success');
                } else {
                    showToast(res.msg, 'error');
                }
            }
        });
    });
</script>