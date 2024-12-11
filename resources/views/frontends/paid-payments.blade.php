@extends('frontends.layouts.main')
@section('main-container')
<div class="paid-payments-page">
    
    <div class="task-board" id="paid-payment-page-tboard">
        <!-- Website Column -->
        <div class="task-column" id="due">
            <div class="todo-heading-payments">
                <img src="{{ url('public/frontend/images/web.png') }}" alt="">
                <h3>Website</h3>
            </div>
            <div class="task-list">
                @foreach ($payments->where('category', 'Website') as $payment)
                <div class="task">
                <div class="task-name">
                        <a href="{{url ('paymentdetails/' .$payment->id)}}">
                            <p>{{ $payment->company_name }}</p>
                        </a>
                    </div>
                    <div class="category">
                        <img src="{{url ('public/frontend/images/category.png')}}" alt=""> : {{ $payment->category}}
                    </div>

                    <div class="inquiry-date">
                          <strong style="margin-right: 4px;">NPR: </strong> {{ $payment->amount }}
                    </div>
                   
                    <div class="paid-date"> 
                        <strong>Paid on:</strong> {{ $payment->paid_date }}
                    </div>
                </div>
                @endforeach
            </div>
            <div class="total-category">
                <div class="paid-label">
                    {{ $paidLabel }}: 
                </div>
                <div class="cat-payment">
                    NPR {{ number_format($totalWebsite) }}
                </div>
            </div>
        </div>

        <!-- Microsoft Column -->
        <div class="task-column" id="invoice_sent">
            <div class="invoicesent-heading">
                <img src="{{ url('public/frontend/images/microsoft.png') }}" alt="">
                <h3>Microsoft</h3>
            </div>
            <div class="task-list">
                @foreach ($payments->where('category', 'Microsoft') as $payment)
                <div class="task">
                    <div class="task-name">
                        <a href="{{url ('paymentdetails/' .$payment->id)}}">
                            <p>{{ $payment->company_name }}</p>
                        </a>
                    </div>
                    <div class="category">
                        <img src="{{url ('public/frontend/images/category.png')}}" alt=""> : {{ $payment->category}}
                    </div>

                    <div class="inquiry-date">
                          <strong style="margin-right: 4px;">NPR: </strong> {{ $payment->amount }}
                    </div>
                   
                    <div class="paid-date"> 
                        <strong>Paid on:</strong> {{ $payment->paid_date }}
                    </div>
                </div>
                @endforeach
            </div>
            <div class="total-category">
                <div class="paid-label">
                    {{ $paidLabel }}: 
                </div>
                <div class="cat-payment">
                    NPR {{ number_format($totalMicrosoft) }}
                </div>
            </div>
        </div>

        <!-- Renewals Column -->
        <div class="task-column" id="vatbill_sent">
            <div class="vatbillsent-heading">
                <img src="{{ url('public/frontend/images/renew.png') }}" alt="">
                <h3>Renewals</h3>
            </div>
            <div class="task-list">
                @foreach ($payments->where('category', 'Renewal') as $payment)
                <div class="task">
                    <div class="task-name">
                        <a href="{{url ('paymentdetails/' .$payment->id)}}">
                            <p>{{ $payment->company_name }}</p>
                        </a>
                    </div>
                    <div class="category">
                        <img src="{{url ('public/frontend/images/category.png')}}" alt=""> : {{ $payment->category}}
                    </div>

                    <div class="inquiry-date">
                          <strong style="margin-right: 4px;">NPR: </strong> {{ $payment->amount }}
                    </div>
                   
                    <div class="paid-date"> 
                        <strong>Paid on:</strong> {{ $payment->paid_date }}
                    </div>
                </div>
                @endforeach
            </div>
            <div class="total-category">
                <div class="paid-label">
                    {{ $paidLabel }}: 
                </div>
                <div class="cat-payment">
                    NPR {{ number_format($totalRenewal) }}
                </div>
            </div>
        </div>

        <!-- Others Column -->
        <div class="task-column" id="paid">
            <div class="paid-heading">
                <img src="{{ url('public/frontend/images/others.png') }}" alt="">
                <h3>Others</h3>
            </div>
            <div class="task-list">
                @foreach ($payments->whereNotIn('category', ['Website', 'Microsoft', 'Renewal']) as $payment)
                <div class="task">
                    <div class="task-name">
                        <a href="{{url ('paymentdetails/' .$payment->id)}}">
                            <p>{{ $payment->company_name }}</p>
                        </a>
                    </div>
                    <div class="category">
                        <img src="{{url ('public/frontend/images/category.png')}}" alt=""> : {{ $payment->category}}
                    </div>

                    <div class="inquiry-date">
                          <strong style="margin-right: 4px;">NPR: </strong> {{ $payment->amount }}
                    </div>
                   
                    <div class="paid-date"> 
                        <strong>Paid on:</strong> {{ $payment->paid_date }}
                    </div>
                </div>
                @endforeach
            </div>
            <div class="total-category">
                <div class="paid-label">
                    {{ $paidLabel }}: 
                </div>
                <div class="cat-payment">
                    NPR {{ number_format($totalOthers) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Total Paid Amount -->
    <div class="total-amounts-paid">
        <div class="total-paid-label">
            {{ $paidLabel }}:
        </div>
        <div class="total-paid-amount">
            NPR {{ number_format($totalPaidAmount) }}
        </div>
    </div>
</div>




<style>
  .paid-payments-page {
        margin-top: 30px;
        margin-left: 20px
    }
    #paid-payment-page-tboard {
        height: 80vh;
    }
    .total-category {
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }
    .paid-label {
    font-weight: 500;
    margin-right: 5px;
}
.cat-payment {
    color: #087641;
    font-weight: 500
}
.total-amounts-paid {
    display: flex;
    align-items: center;
    justify-content: flex-start;
}
.total-paid-label {
    font-weight: 500;
    margin-right: 5px;
}
.total-paid-amount {
    color: #087641;
    font-weight: 500
}

.todo-heading-payments {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #e16b16;
    width: 90px; 
    padding: 4px 8px 4px 4px;
    border-radius: 7px;
    margin-top: 0px;
    margin-bottom: 5px;
}
.todo-heading-payments img {
    width: 14px;

}
.todo-heading-payments h3 {
    font-size: 14px;
}
.invoicesent-heading {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f7b04f;
    width: 100px; 
    padding: 4px 8px 4px 4px;
    border-radius: 7px;
    margin-bottom: 5px;
}
.invoicesent-heading img {
    width: 14px;
    margin-right: 10px;
}
.invoicesent-heading h3 {
    font-size: 14px;
}
.vatbillsent-heading {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #4f72f3;
    width: 100px; 
    padding: 4px 8px 4px 4px;
    border-radius: 7px;
    margin-bottom: 5px;
}
.vatbillsent-heading img {
    width: 14px;
    margin-right: 10px;
}
.vatbillsent-heading h3 {
   font-size: 14px;
}
.paid-heading {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #318945;
    width: 80px; 
    padding: 4px 8px 4px 4px;
    border-radius: 7px;
    margin-bottom: 5px;
}
.paid-heading img {
    width: 14px;
    margin-right: 10px;
}
.paid-heading h3 {
    font-size: 14px;
}
.closed-heading-prospect {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #318945;
    width: 125px; 
    padding: 4px 8px 4px 4px;
    border-radius: 7px;
      margin-top: -7px;
    margin-bottom: 5px;
}
.closed-heading-prospect img {
    width: 14px;
    margin-right: 10px;
}
.closed-heading-prospect h3 {
    font-size: 14px;
}

  

</style>



@endsection