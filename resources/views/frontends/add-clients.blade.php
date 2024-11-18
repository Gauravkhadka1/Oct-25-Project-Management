@extends ('frontends.layouts.main')

@section ('main-container')

<div class="add-clients-page">

    <div class="form-container">
        <h2>Add Client Details</h2>
        <form action="{{ route('clients.store') }}" method="POST">
    @csrf
   
    <div class="company-details">
        <div class="form-group">
            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" nullable>
        </div>
        <div class="form-group">
            <label for="website">Website:</label>
            <input type="text" id="website" name="website" nullable>
        </div>

        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" nullable>
                <option value="">Select Category</option>
                <option value="Website">Website</option>
                <option value="Microsoft">Microsoft</option>
                <option value="Hosting">Hosting</option>
            </select>
        </div>

        <!-- Subcategory Selection -->
        <div class="form-group" id="subcategory-container" style="display: none;">
            <label for="subcategory">Subcategory:</label>
            <select id="subcategory" name="subcategory" nullable>
                <option value="">Select Subcategory</option>
            </select>
        </div>

        <!-- Additional Subcategory Selection -->
        <div class="form-group" id="additional-subcategory-container" style="display: none;">
            <label for="additional_subcategory">Additional Subcategory:</label>
            <select id="additional_subcategory" name="additional_subcategory" nullable>
                <option value="">Select Additional Subcategory</option>
            </select>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="3"nullable></textarea>
        </div>

        <div class="form-group">
            <label for="company_phone">Company Phone:</label>
            <input type="tel" id="company_phone" name="company_phone" nullable>
        </div>

        <div class="form-group">
            <label for="company_email">Company Email:</label>
            <input type="email" id="company_email" name="company_email" nullable>
        </div>
    </div>

    <div class="contact-person-details">
        <div class="form-group">
            <label for="contact_person">Contact Person:</label>
            <input type="text" id="contact_person" name="contact_person" nullable>
        </div>

        <div class="form-group">
            <label for="contact_person_phone">Contact Person Phone:</label>
            <input type="tel" id="contact_person_phone" name="contact_person_phone" nullable>
        </div>

        <div class="form-group">
            <label for="contact_person_email">Contact Person Email:</label>
            <input type="email" id="contact_person_email" name="contact_person_email" nullable>
        </div>
    </div>

    <button type="submit" class="submit-btn">Add Client</button>
</form>

    </div>
</div>
<script>
   document.addEventListener('DOMContentLoaded', function () {
    const categoryData = {
        "Website": {
            "Company": ["Manpower", "Hydropower", "Other"],
            "NGO/ INGO": [],
            "Tourism": [],
            "Education": ["Edu Consultancy", "School", "College", "Other"],
            "eCommerce": ["Product Catlog", "ecommerce", "Other"],
            "Hospitality": ["Hotel & Cafe", "Resort", "Other"],
            "other": [""]
        },
        "Microsoft": {
            "Paid": [],
            "Non Profit": ["Education", "NGO/ INGO"]
        },
        "Hosting": {
            
        },
        "Other": {
            
        }
    };

    const categorySelect = document.getElementById('category');
    const subcategorySelect = document.getElementById('subcategory');
    const additionalSubcategorySelect = document.getElementById('additional_subcategory');
    const subcategoryContainer = document.getElementById('subcategory-container');
    const additionalSubcategoryContainer = document.getElementById('additional-subcategory-container');

    categorySelect.addEventListener('change', function () {
        const selectedCategory = categorySelect.value;
        subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
        additionalSubcategorySelect.innerHTML = '<option value="">Select Additional Subcategory</option>';
        additionalSubcategoryContainer.style.display = 'none';

        if (selectedCategory) {
            subcategoryContainer.style.display = 'block';
            const subcategories = Object.keys(categoryData[selectedCategory]);
            subcategories.forEach(function (subcategory) {
                const option = document.createElement('option');
                option.value = subcategory;
                option.text = subcategory;
                subcategorySelect.appendChild(option);
            });
        } else {
            subcategoryContainer.style.display = 'none';
        }
    });

    subcategorySelect.addEventListener('change', function () {
        const selectedCategory = categorySelect.value;
        const selectedSubcategory = subcategorySelect.value;
        additionalSubcategorySelect.innerHTML = '<option value="">Select Additional Subcategory</option>';

        if (selectedSubcategory && categoryData[selectedCategory][selectedSubcategory]) {
            additionalSubcategoryContainer.style.display = 'block';
            const additionalSubcategories = categoryData[selectedCategory][selectedSubcategory];
            additionalSubcategories.forEach(function (additionalSubcategory) {
                const option = document.createElement('option');
                option.value = additionalSubcategory;
                option.text = additionalSubcategory;
                additionalSubcategorySelect.appendChild(option);
            });
        } else {
            additionalSubcategoryContainer.style.display = 'none';
        }
    });
});

</script>


@endsection