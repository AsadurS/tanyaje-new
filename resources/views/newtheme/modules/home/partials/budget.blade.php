<!-- START budgetinAea -->
<section class="budgetinAea">

    <div class="container disflexArea">
        <div class="images">
            <img src="{{asset('new/images/mindImg.png')}}" alt="">
        </div>
        <div class="subText">
            <h2>Have a budget in mind?</h2>
            <p>Let us recommend for you based <br> on your budget. </p>
            {!! Form::open(array('url' =>'carfilters', 'method'=>'get', 'class' => 'form-horizontal form-validate')) !!}
                <div class="myrBox disflexArea algnflexArea">
                    <div class="subBox">
                        <label>Myr</label>
                        <input id="start_price" name="start_price" type="text" placeholder="Min." required>
                    </div>
                    <strong>to</strong>
                    <div class="subBox">
                        <label>Myr</label>
                        <input  id="start_price" name="last_price" type="text" placeholder="Max." required>
                    </div>
                </div>
                <button type="submit" class="Searchbtn">Search</button>
            {!! Form::close() !!}
        </div>
    </div>
</section>
<!-- END budgetinAea -->