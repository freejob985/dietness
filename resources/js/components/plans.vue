<template>
    <div>
        <div class="row">
         <div class="col-lg-12">
         <button  class="btn btn-primary mr-2" @click="create_new_plan()">اضافة خطة جديدة</button>
     </div>
    </div>
       <table class="table" style="margin-top: 5px;">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">الخطة</th>
      <th scope="col">سعر الخطة</th>
      <th scope="col">تفاصيل الخطة</th>
      <th scope="col">خيارات</th>
    </tr>
  </thead>
  <tbody>
     <tr v-for="(plan,index) in plans">
      <td scope="col">{{index+1}}</td>
      <td scope="col">
        <span v-for="(category,index) in plan.categories"><span v-if="category.category">{{category.qty}} {{category.category.title_ar}}</span> <span v-if="index != plan.categories.length - 1">, </span></span>
    </td>
      <td scope="col">{{plan.price}} د.ك</td>
      <td scope="col">{{plan.description_ar}}</td>
      <td scope="col">
          <button @click="fire_plan_edit_modal(plan)" class="btn btn-primary mr-2">تعديل</button>
          <button @click="fire_plan_remove_modal(plan)" class="btn btn-danger mr-2">حذف</button>
    
    </td>
    </tr>
    
    </tbody>
</table>
    

<!-- Modal-->
<div class="modal fade" id="plan_edit" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">تعديل الخطة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" v-if="single_plan">
                <div class="form-group row">
                            <label class="col-lg-3 col-form-label">السعر :</label>
                            <div class="col-lg-6">
                                <input type="number" step=".1" class="form-control" v-model="single_plan.price">
                            </div>
                        </div>
                  <div class="form-group row">
                            <label class="col-lg-3 col-form-label">عدد ايام الاشتراك :</label>
                            <div class="col-lg-6">
                                <input type="number" step="1" class="form-control" v-model="single_plan.days">
                            </div>
                        </div>
                 <div class="form-group row">
                            <label class="col-lg-3 col-form-label">التفاصيل باللغة الانجليزية</label>
                            <div class="col-lg-6">
                                <textarea class="form-control" rows="3" v-model="single_plan.description_en"></textarea>
                            </div>
                        </div>
                    <div class="form-group row">
                            <label class="col-lg-3 col-form-label">التفاصيل باللغة اللغة العربية</label>
                            <div class="col-lg-6">
                                <textarea class="form-control" rows="3" v-model="single_plan.description_ar"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">وصف اختياري باللغة الانجليزية</label>
                            <div class="col-lg-6">
                                <textarea class="form-control" rows="3" v-model="single_plan.sub_description_en"></textarea>
                            </div>
                        </div>
                    <div class="form-group row">
                            <label class="col-lg-3 col-form-label">وصف اختياري باللغة اللغة العربية</label>
                            <div class="col-lg-6">
                                <textarea class="form-control" rows="3" v-model="single_plan.sub_description_ar"></textarea>
                            </div>
                        </div>
                <h5 class="texth5">الاقسام</h5>
                <div class="row" v-for="(category_obj,category_index) in single_plan.categories">
    <div class="col-lg-4">
        <div class="form-group">
        <label>القسم</label>
        <select class="form-control form-control-solid" v-model="category_obj.category">
            <option value="" selected>اختر القسم</option>
            <option v-for="(category,index) in categories" :selected="category_obj.category.id == category.id" :value="category">{{category.title_ar}}</option>
    </select>
    </div>
    </div>
      <div class="col-lg-2"><div class="form-group">
														<label>الكمية</label>
														<input type="number" class="form-control form-control-solid" v-model="category_obj.qty">
													</div></div>
    <div class="col-lg-2"><div class="form-group">
														<label>الحد الاقصي</label>
														<input type="number" class="form-control form-control-solid" v-model="category_obj.max">
													</div></div>
    <div class="col-lg-2"><div class="form-group">
														<label>الحد الادني</label>
														<input type="number" class="form-control form-control-solid" v-model="category_obj.min">
													</div></div>
        <div class="col-lg-2 displayFlex">
        <i class="icon-xl la la-trash" @click="remove_category(category_index)"></i>	        
    </div>
    </div>
                <button type="button" class="btn btn-primary btn-sm" @click="add_new_category()">اضافة قسم</button>
                <div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
        <div class="alert alert-success messages" role="alert" v-if="responseSuccess.status">
   <p>{{responseSuccess.message}}</p>
</div>
                    <div class="alert alert-danger errors" role="alert" v-if="validations_errors.status">
    <p v-for="error in validations_errors.errors">{{error}}</p>
</div>
    </div>
    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">الغاء</button>
                <button type="button" class="btn btn-primary font-weight-bold" @click="savePlan()">حفظ</button>
            </div>
        </div>
    </div>
</div>
        <!-- Modal-->
<div class="modal fade" id="plan_create" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">تعديل الخطة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" v-if="new_plan">
                <div class="form-group row">
                            <label class="col-lg-3 col-form-label">السعر :</label>
                            <div class="col-lg-6">
                                <input type="number" step=".1" class="form-control" v-model="new_plan.price">
                            </div>
                        </div>
                 <div class="form-group row">
                            <label class="col-lg-3 col-form-label">عدد ايام الاشتراك :</label>
                            <div class="col-lg-6">
                                <input type="number" step="1" class="form-control" v-model="new_plan.days">
                            </div>
                        </div>
                 <div class="form-group row">
                            <label class="col-lg-3 col-form-label">التفاصيل باللغة الانجليزية</label>
                            <div class="col-lg-6">
                                <textarea class="form-control" rows="3" v-model="new_plan.description_en"></textarea>
                            </div>
                        </div>
                    <div class="form-group row">
                            <label class="col-lg-3 col-form-label">التفاصيل باللغة اللغة العربية</label>
                            <div class="col-lg-6">
                                <textarea class="form-control" rows="3" v-model="new_plan.sub_description_ar"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">وصف اختياري باللغة الانجليزية</label>
                            <div class="col-lg-6">
                                <textarea class="form-control" rows="3" v-model="new_plan.sub_description_en"></textarea>
                            </div>
                        </div>
                    <div class="form-group row">
                            <label class="col-lg-3 col-form-label">وصف اختياري باللغة اللغة العربية</label>
                            <div class="col-lg-6">
                                <textarea class="form-control" rows="3" v-model="new_plan.description_ar"></textarea>
                            </div>
                        </div>
                <h5 class="texth5">الاقسام</h5>
                <div class="row" v-for="(category_obj,category_index) in new_plan.categories">
    <div class="col-lg-4">
        <div class="form-group">
        <label>القسم</label>
        <select class="form-control form-control-solid" v-model="category_obj.category">
            <option value="" selected>اختر القسم</option>
            <option v-for="(category,index) in categories" :selected="category_obj.category.id == category.id" :value="category">{{category.title_ar}}</option>
    </select>
    </div>
    </div>
    <div class="col-lg-2"><div class="form-group">
														<label>الكمية</label>
														<input type="number" class="form-control form-control-solid" v-model="category_obj.qty">
													</div></div>
    <div class="col-lg-2"><div class="form-group">
														<label>الحد الاقصي</label>
														<input type="number" class="form-control form-control-solid" v-model="category_obj.max">
													</div></div>
    <div class="col-lg-2"><div class="form-group">
														<label>الحد الادني</label>
														<input type="number" class="form-control form-control-solid" v-model="category_obj.min">
													</div></div>
        <div class="col-lg-2 displayFlex">
        <i class="icon-xl la la-trash" @click="remove_category_new(category_index)"></i>	        
    </div>
    </div>
                <button type="button" class="btn btn-primary btn-sm" @click="add_new_category_new()">اضافة قسم</button>
                <div class="row" style="margin-top: 20px;">
  <div class="col-lg-12">
        <div class="alert alert-success messages" role="alert" v-if="responseSuccess.status">
   <p>{{responseSuccess.message}}</p>
</div>
                    <div class="alert alert-danger errors" role="alert" v-if="validations_errors.status">
    <p v-for="error in validations_errors.errors">{{error}}</p>
</div>
    </div>
    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">الغاء</button>
                <button type="button" class="btn btn-primary font-weight-bold" @click="savePlan_new()">حفظ</button>
            </div>
        </div>
    </div>
</div>
       <!-- Modal-->
<div class="modal fade" id="confirm_remove" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">رسالة تأكيدية</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body text-center">
                <h5>من فضلك قم بالتأكيد لامكانية الحذف .</h5>
                       <div class="row" style="margin-top: 20px;">
                      <div class="col-lg-12">
                            <div class="alert alert-success messages" role="alert" v-if="responseSuccess.status">
                       <p>{{responseSuccess.message}}</p>
                    </div>
                                        <div class="alert alert-danger errors" role="alert" v-if="validations_errors.status">
                        <p v-for="error in validations_errors.errors">{{error}}</p>
                    </div>
                        </div>
                        </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">الغاء</button>
                <button type="button" class="btn btn-primary font-weight-bold" id="remove_confirm_btn" @click="confirm_plan_trash()">تأكيد</button>
            </div>
        </div>
    </div>
</div> 
    </div>
</template>

<script>
    export default {
        props: ['package_id'],
         data(){
        return {
             package : null,
             plans : [],
             single_plan : null,
             categories : [],
             category_obj_example : {"id":null,"plan":null,"category": '',"qty" : 0,"max":0,"min":0,"created_at":null,"updated_at":null},
             validations_errors : {"status" : false , 'errors' : []},
             responseSuccess : {'status' : false , 'message' : ''},
             new_plan : {price : null , days : null, description_en : '' , description_ar : '',sub_description_en : '' , sub_description_ar : '', categories : [{"id":null,"plan":null,"category": '',"qty" : 0,"max":0,"min":0,"created_at":null,"updated_at":null}]},
            empty_plan_object : {price : null , days : null , description_en : '' , description_ar : '', sub_description_en : '' , sub_description_ar : '',categories : [{"id":null,"plan":null,"category": '',"qty" : 0,"max":0,"min":0,"created_at":null,"updated_at":null}]},
            plan_remove : null,
        }
    },
        mounted() {
            this.package = this.package_id;
            axios.get(`/admin/packages/${this.package_id}/plans`).then((data)=>{
                if(data.data){
                    this.plans = data.data.package.plans;
                    this.categories = data.data.categories;
                }
                console.log(data);
            });
        },
        methods:{
            fire_plan_edit_modal(plan){
                this.single_plan = plan;
                $('#plan_edit').modal('show');
            },
            savePlan(){
                this.responseSuccess.status = false;
                this.validations_errors.status = false;
                axios.post(`/admin/plans/edit/${this.single_plan.id}`,{'plan': this.single_plan}).then((data)=>{
                   if(data.status == 200){
                       this.responseSuccess.status = true;
                       this.responseSuccess.message = data.data.message;
                       var item = this.plans.findIndex(item => item.id === this.single_plan.id);
                       this.plans[item].categories = this.single_plan.categories;
                       setTimeout(_=>{
                           $('#plan_edit').modal('hide');
                           this.responseSuccess.status = false;
                           this.validations_errors.status = false;
                       },2000);
                   }
                    
            }).catch((error)=>{
                    if(error.response.status == 422){
                        this.validations_errors.status = true;
                        this.validations_errors.errors = error.response.data;
                    }
                     });
            },
          add_new_category(){
                console.log(this.categories);
                console.log(this.single_plan.categories);
                if(this.single_plan.categories.length < this.categories.length){
                    this.single_plan.categories.push(JSON.parse(JSON.stringify(this.category_obj_example)));
                }
            },
          remove_category(index){
                this.single_plan.categories.splice(index, 1);
            },
          create_new_plan(){
              $('#plan_create').modal('show');
          },
            add_new_category_new(){
                if(this.new_plan.categories.length < this.categories.length){
                    this.new_plan.categories.push(JSON.parse(JSON.stringify(this.category_obj_example)));
                }
            },
          remove_category_new(index){
                this.new_plan.categories.splice(index, 1);
            },
            savePlan_new(){
                this.responseSuccess.status = false;
                this.validations_errors.status = false;
                axios.post(`/admin/plans/add/${this.package}`,{'plan': this.new_plan}).then((data)=>{
                   if(data.status == 200){
                       this.responseSuccess.status = true;
                       this.responseSuccess.message = data.data.message;
                       this.plans.push(data.data.data);
                       this.new_plan = this.empty_plan_object;
                       setTimeout(_=>{
                           $('#plan_create').modal('hide');
                           this.responseSuccess.status = false;
                           this.validations_errors.status = false;
                       },2000);
                   }
                    
            }).catch((error)=>{
                    if(error.response.status == 422){
                        this.validations_errors.status = true;
                        this.validations_errors.errors = error.response.data;
                    }
                     });
            },
            fire_plan_remove_modal(plan){
                this.plan_remove = plan;
                $('#confirm_remove').modal('show');
            },
            confirm_plan_trash(){
                 axios.post(`/admin/plans/remove`,{'plan': this.plan_remove.id}).then((data)=>{
                   if(data.status == 200){
                     this.responseSuccess.status = true;
                     this.responseSuccess.message = data.data.message;
                     this.plans = this.plans.filter((el) => { return el.id != this.plan_remove.id; });
                     this.plan_remove = null;
                       setTimeout(_=>{
                           $('#confirm_remove').modal('hide');
                           this.responseSuccess.status = false;
                           this.validations_errors.status = false;
                       },3000);
                   }
                    
            }).catch((error)=>{
                    if(error.response.status == 422){
                        this.validations_errors.status = true;
                        this.validations_errors.errors = error.response.data;
                    }
                     });
            }
        }
    }
</script>
