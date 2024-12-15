@extends('frontends.layouts.main')

@section('main-container')

<div class="client-detail-page">
    <h2> <img src="{{url('public/frontend/images/user.png')}}" alt="">Client Info</h2>
    <form action="{{ route('client.update', ['id' => $client->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="toastBox">

        </div>
        <div class="client-informations">
            <div class="contact-information">
                <div class="company-informations">
                    <div class="client-details-form">
                        <label for="comapny_name">Company Name</label>
                        <input type="text" id="company_name" name="company_name" value="{{ $client->company_name }}">
                    </div>
                    <div class="client-details-form">
                        <label for="domain_name">Domain</label>
                        <input type="text" id="website" name="website" value="{{ $client->website }}">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="{{ $client->address }}">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Company Phone</label>
                        <input type="text" id="company_phone" name="company_phone" value="{{ $client->company_phone }}">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Company Email</label>
                        <input type="email" id="comapny_email" name="company_email" value="{{ $client->company_email }}">
                    </div>
                    <div class="client-details-form">
                        <label for="vat_no">PAN/ VAT Number</label>
                        <input type="text" id="vat_no" name="vat_no" value="{{ $client->vat_no }}">
                    </div>
                </div>
                <div class="company-contact-person-informations">
                    <div class="client-details-form">
                        <label for="address">Contact Person </label>
                        <input type="text" id="contact_person" name="contact_person" value="{{ $client->contact_person  }}">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Contact Person Phone</label>
                        <input type="text" id="contact_person_phone" name="contact_person_phone" value="{{ $client->contact_person_phone }}">
                    </div>
                    <div class="client-details-form">
                        <label for="address">Contact Person Email </label>
                        <input type="email" id="contact_person_email" name="contact_person_email" value="{{ $client->contact_person_email  }}">
                    </div>
                </div>
            </div>
            <div class="additional-informations">
                <div class="general-info">
                    <div class="client-details-form">
                        <label for="additional_info">Additional Informations</label>
                        <textarea id="summernote" name="additional_info">{{$client->additional_info}}</textarea>
                    </div>
                </div>
            </div>
            <div class="additional-informations">
                <div class="general-info">
                    <div class="client-details-form">
                        <label for="additional_info">Additional Informations</label>
                        <textarea id="summernote" name="additional_info">{{$client->additional_info}}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="client-services">
            <div class="domain-service">
                <h2> <img src="{{url ('public/frontend/images/design-blue.png')}}" alt=""> Web Design</h2>
                <!-- <div class="client-details-form">
                        <label for="domain_name">Domain Name</label>
                        <input type="text" id="domain_name" name="domain_name" >
                    </div> -->
                <!-- <div class="client-details-form">
                <label for="domain_active_date">Active Date</label>
                <input type="date" id="domain_active_date" name="domain_active_date" value="{{ $client->domain_active_date}}">
            </div>
            <div class="client-details-form">
                <label for="domain_active_date">Expiry Date</label>
                <input type="date" id="domain_expiry_date" name="domain_expiry_date" value="{{ $client->domain_expiry_date}}">
            </div> -->
                <div class="client-details-form">
                    <label for="choose_category">Category </label>
                    <select id="choose_subcategory" name="subcategory">
                        <option value="">Select Category</option>
                        <option value="Company Portfolio">Company Portfolio</option>
                        <option value="NGO/ INGO">NGO/ INGO</option>
                        <option value="Tourism">Tourism</option>
                        <option value="Ecommerce">Ecommerce</option>
                        <option value="Hotels/ Cafe">Hotels/ Cafe</option>
                        <option value="Education Consultancy">Education Consultancy</option>
                        <option value="Manpower">Manpower</option>
                        <option value="News Portal">News Portal</option>
                        <option value="Health">Health</option>
                        <option value="Personal Portfolio">Personal Portfolio</option>
                    </select>
                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="domain_amount" name="domain_amount" value="{{ $client->domain_amount}}">
                </div>
                <div class="client-details-form">
                    <label for="contract">Upload Contract</label>
                    <input type="file" id="contract" name="contract" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                </div>


                <div class="client-details-form">
                    <label>View Contract</label>
                    @if ($client->contract)
                    <div class="contract-preview">
                        @php
                        $filePath = Storage::url($client->contract);
                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                        @endphp

                        @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                        <!-- Image Preview -->
                        <a href="{{ $filePath }}" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="{{ $filePath }}" alt="Contract Image">
                            </div>

                        </a>
                        @elseif (in_array($fileExtension, ['pdf']))
                        <!-- PDF Icon -->
                        <a href="{{ $filePath }}" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="{{ url('public/frontend/images/pdf.png') }}" alt="PDF Icon">
                            </div>

                        </a>
                        @elseif (in_array($fileExtension, ['doc', 'docx']))
                        <!-- Word Icon -->
                        <a href="{{ $filePath }}" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="{{ url('public/frontend/images/word.png') }}" alt="Word Icon">
                            </div>

                        </a>
                        @else
                        <p>Unsupported file type. <a href="{{ $filePath }}" target="_blank">Download</a></p>
                        @endif
                    </div>
                    @else
                    <p>No contract uploaded.</p>
                    @endif
                </div>

            </div>
            <div class="domain-service">
                <h2> <img src="{{url ('public/frontend/images/domain.png')}}" alt=""> Domain</h2>
                <!-- <div class="client-details-form">
                        <label for="domain_name">Domain Name</label>
                        <input type="text" id="domain_name" name="domain_name" >
                    </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="domain_active_date" name="domain_active_date" value="{{ $client->domain_active_date}}">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="domain_expiry_date" name="domain_expiry_date" value="{{ $client->domain_expiry_date}}">
                </div>
                <div class="client-details-form">
                    <label for="choose_domain">Choose Domain </label>
                    <select id="choose_domain" name="domain_type">
                        <option value="">Select Domain</option>
                        <option value="Site5" {{ $client->domain_type == 'Site5' ? 'selected' : '' }}>Site5</option>
                        <option value="Hostforweb" {{ $client->domain_type == 'Hostforweb' ? 'selected' : '' }}>Hostforweb</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="domain_amount" name="domain_amount" value="{{ $client->domain_amount}}">
                </div>
            </div>
            <div class="domain-service">
                <h2> <img src="{{url('public/frontend/images/hosting.png')}}" alt=""> Hosting</h2>
                <!-- <div class="client-details-form">
                        <label for="domain_name">Domain Name</label>
                        <input type="text" id="domain_name" name="domain_name" >
                    </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="hosting_active_date" name="hosting_active_date" value="{{ $client->hosting_active_date}}">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="hosting_expiry_date" name="hosting_expiry_date" value="{{ $client->hosting_expiry_date}}">
                </div>
                <div class="client-details-form">
                    <label for="hosting_space">Hosting Space</label>
                    <input type="text" id="hosting_space" name="hosting_space" value="{{ $client->hosting_space}}">
                </div>
                <div class="client-details-form">
                    <label for="choose_hosting">Choose Hosting</label>
                    <select id="choose_hosting" name="hosting_type">
                        <option value="">Select Hosting</option>
                        <option value="Site5" {{ $client->hosting_type == 'Site5' ? 'selected' : '' }}>Site5</option>
                        <option value="Hostforweb" {{ $client->hosting_type == 'Hostforweb' ? 'selected' : '' }}>Hostforweb</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="hosting_amount" name="hosting_amount" value="{{$client->hosting_amount}}">
                </div>
            </div>
            <div class="domain-service">
                <h2><img src="{{url('public/frontend/images/microsoft.png')}}">Microsoft</h2>
                <!-- <div class="client-details-form">
                        <label for="domain_name">Domain Name</label>
                        <input type="text" id="domain_name" name="domain_name" >
                    </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="microsoft_active_date" name="microsoft_active_date" value="{{$client->microsoft_active_date}}">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="microsoft_expiry_date" name="microsoft_expiry_date" value="{{$client->microsoft_expiry_date}}">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">No of Liscense</label>
                    <input type="text" id="no_of_license" name="no_of_license" value="{{$client->no_of_license}}">
                </div>
                <div class="client-details-form">
                    <label for="choose_hosting">Choose Type</label>
                    <select id="choose_type" name="microsoft_type">
                        <option value="">Select Type</option>
                        <option value="Paid" {{ $client->microsoft_type == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Non-Profit" {{ $client->microsoft_type == 'Non-Profit' ? 'selected' : '' }}>Non-Profit</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="microsoft_amount" name="microsoft_amount" value="{{$client->microsoft_amount}}">
                </div>
            </div>
            <div class="domain-service">
                <h2> <img src="{{url('public/frontend/images/maintenance.png')}}">Maintenance</h2>
                <!-- <div class="client-details-form">
                        <label for="domain_name">Domain Name</label>
                        <input type="text" id="domain_name" name="domain_name" >
                    </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="maintenance_active_date" name="maintenance_active_date" value="{{$client->maintenance_active_date}}">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="maintenance_expiry_date" name="maintenance_expiry_date" value="{{$client->maintenance_expiry_date}}">
                </div>
                <div class="client-details-form">
                    <label for="choose_maintenance">Choose Package</label>
                    <select id="choose_maintenance" name="maintenance_type">
                        <option value="">Select Package</option>
                        <option value="Basic" {{ $client->maintenance_type == 'Basic' ? 'selected' : '' }}>Basic</option>
                        <option value="Advance" {{ $client->maintenance_type == 'Advance' ? 'selected' : '' }}>Advance</option>
                        <option value="Standard" {{ $client->maintenance_type == 'Standard' ? 'selected' : '' }}>Standard</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="maintenance_amount" name="maintenance_amount" value="{{$client->maintenance_amount}}">
                </div>
                <div class="client-details-form">
                    <label for="maintenance_contract">Upload Contract</label>
                    <input type="file" id="maintenance_contract" name="maintenance_contract" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                </div>

                <div class="client-details-form">
                    <label>View Maintenance Contract</label>
                    @if ($client->maintenance_contract)
                    <div class="contract-preview">
                        @php
                        $filePath = Storage::url($client->maintenance_contract);
                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                        @endphp

                        @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                        <!-- Image Preview -->
                        <a href="{{ $filePath }}" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="{{ $filePath }}" alt="Maintenance Contract Image">
                            </div>
                        </a>
                        @elseif (in_array($fileExtension, ['pdf']))
                        <!-- PDF Icon -->
                        <a href="{{ $filePath }}" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="{{ url('public/frontend/images/pdf.png') }}" alt="PDF Icon">
                            </div>
                        </a>
                        @elseif (in_array($fileExtension, ['doc', 'docx']))
                        <!-- Word Icon -->
                        <a href="{{ $filePath }}" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="{{ url('public/frontend/images/word.png') }}" alt="Word Icon">
                            </div>
                        </a>
                        @else
                        <p>Unsupported file type. <a href="{{ $filePath }}" target="_blank">Download</a></p>
                        @endif
                    </div>
                    @else
                    <p>No contract uploaded.</p>
                    @endif
                </div>
            </div>
            <div class="domain-service">
                <h2> <img src="{{url('public/frontend/images/seo.png')}}">SEO</h2>
                <!-- <div class="client-details-form">
                        <label for="domain_name">Domain Name</label>
                        <input type="text" id="domain_name" name="domain_name" >
                    </div> -->
                <div class="client-details-form">
                    <label for="domain_active_date">Active Date</label>
                    <input type="date" id="seo_active_date" name="seo_active_date" value="{{$client->seo_active_date}}">
                </div>
                <div class="client-details-form">
                    <label for="domain_active_date">Expiry Date</label>
                    <input type="date" id="seo_expiry_date" name="seo_expiry_date" value="{{$client->seo_expiry_date}}">
                </div>
                <div class="client-details-form">
                    <label for="choose_seo">Choose SEO </label>
                    <select id="choose_seo" name="seo_type">
                        <option value="">Select SEO</option>
                        <option value="Basic" {{ $client->seo_type == 'Basic' ? 'selected' : '' }}>Basic</option>
                        <option value="Advance" {{ $client->seo_type == 'Advance' ? 'selected' : '' }}>Advance</option>
                    </select>

                </div>
                <div class="client-details-form">
                    <label for="amount">Amount</label>
                    <input type="text" id="seo_amount" name="seo_amount" value="{{$client->seo_amount}}">
                </div>
                <div class="client-details-form">
                    <label for="seo_contract">Upload SEO Contract</label>
                    <input type="file" id="seo_contract" name="seo_contract" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                </div>

                <div class="client-details-form">
                    <label>View SEO Contract</label>
                    @if ($client->seo_contract)
                    <div class="contract-preview">
                        @php
                        $filePath = Storage::url($client->seo_contract);
                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                        @endphp

                        @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                        <!-- Image Preview -->
                        <a href="{{ $filePath }}" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="{{ $filePath }}" alt="SEO Contract Image">
                            </div>
                        </a>
                        @elseif (in_array($fileExtension, ['pdf']))
                        <!-- PDF Icon -->
                        <a href="{{ $filePath }}" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="{{ url('public/frontend/images/pdf.png') }}" alt="PDF Icon">
                            </div>
                        </a>
                        @elseif (in_array($fileExtension, ['doc', 'docx']))
                        <!-- Word Icon -->
                        <a href="{{ $filePath }}" target="_blank" class="contract-card">
                            <div class="contract-image">
                                <img src="{{ url('public/frontend/images/word.png') }}" alt="Word Icon">
                            </div>
                        </a>
                        @else
                        <p>Unsupported file type. <a href="{{ $filePath }}" target="_blank">Download</a></p>
                        @endif
                    </div>
                    @else
                    <p>No SEO contract uploaded.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="client-details-form">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>


<!-- Include jQuery (required for Summernote) -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Apply Flatpickr to all input[type="date"]
        flatpickr('input[type="date"]', {
            dateFormat: "Y-m-d", // Customize the format as needed
            allowInput: true // Allow manual typing
        });
    });
</script>

<script>
    let toastBox = document.getElementById('toastBox');
    let successMsg = ' <i class="fa-solid fa-circle-check"></i> Client Detail Updated Succesfully';
    let errorMsg = ' <i class="fa-solid fa-circle-xmark"></i> Please fix the error';
    let invalidMsg = '<i class="fa-solid fa-circle-exclamation"></i> Invalid input, check again';

    function showToast(msg) {
        let toast = document.createElement('div');
        toast.classList.add('toast');
        toast.innerHTML = msg;
        toastBox.appendChild(toast);

        if (msg.includes('error')) {
            toast.classList.add('error')
        }
        if (msg.includes('Invalid')) {
            toast.classList.add('invalid')
        }


        setTimeout(() => {
            toast.remove();
        }, 6000);
    }

    // Show the toast message if success exists in session
    @if(session('success'))
    showToast(successMsg);
    @endif
</script>

@endsection