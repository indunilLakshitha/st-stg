<?php

namespace App\Livewire\Admin\Courses\Course;

use App\Models\Course;
use App\Models\CourseCategory;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class UpdateCourse extends Component
{
    use WithFileUploads;

    public $id;
    public $course;
    public $categories;
    public $is_discounted;
    public $name, $category_id, $description,
        $thumbnail, $display_price, $discount,
        $referer_commission, $course_point, $installment_1,
        $installment_2, $has_website =true, $course_price;

    public $description_text;
    public $currentImage;

    public function mount($id)
    {
        $this->id = $id;

        $course = Course::where('id', $id)->first();

        if (!isset($course) || !Auth::user()->is_admin)
            abort(404);

        $this->course = $course;
        $this->categories = CourseCategory::all();

        $this->name = $course->name;
        $this->category_id = $course->category_id;
        $this->description = $course->description;
        $this->display_price = $course->display_price;
        $this->discount = $course->discount;
        $this->referer_commission = $course->referer_commission;
        $this->course_point = $course->course_point;
        $this->installment_1 = $course->installment_1;
        $this->installment_2 = $course->installment_2;
        $course->discount_status = $this->is_discounted;

        if ($course->has_website == 1) {
            $this->has_website = true;
        } else {
            $this->has_website = false;
        }

        $this->course_price = $course->course_price;
        $this->currentImage = $course->thumbnail;
    }

    public function render()
    {
        return view('livewire.admin.courses.course.update-course');
    }

    public function updateCourse()
    {
        // dd($this->thumbnail);
        $this->validate();

        // try {
        DB::beginTransaction();

        $course = $this->course;

        $course->name = $this->name;
        $course->category_id = $this->category_id;
        $course->description = $this->description_text;
        $course->display_price = $this->display_price;
        $course->discount = $this->discount;
        $course->referer_commission = $this->referer_commission;
        $course->course_point = $this->course_point;
        $course->course_price = $this->course_price;
        $course->installment_1 = $this->installment_1;
        $course->installment_2 = $this->installment_2;
        // dd($this->has_website);
        $course->has_website = $this->has_website;


        // Delete the old image if it exists
        if ($this->currentImage  && isset($this->thumbnail)) {

            $this->validate(['thumbnail' => 'image|max:1024']);

            if (Storage::exists('public/' . $this->currentImage)) {
                Storage::delete('public/' . $this->currentImage);
            }

            $course->thumbnail = $this->thumbnail->store('public/photos');
            $course->thumbnail = str_replace("public/", "", $course->thumbnail);
        }


        $course->save();


        DB::commit();
        // $this->clear();

        // return session()->flash('message', 'Course successfully Updated.');
        $this->dispatch('success_alert', ['title' => 'Course successfully Updated']);
        return redirect()->route('admin.course.index');
        // } catch (Exception $e) {

        //     DB::rollBack();
        //     Log::alert('updateCourse :: ' . $e->getMessage());
        // }
    }

    protected function rules()
    {
        return  [
            'name' => 'required',
            'category_id' => 'required',
            // 'description_text' => 'required',
            // 'thumbnail' => 'image|max:1024',
            // 'discount' => 'required',
            'referer_commission' => 'required',
            'course_price' => 'required',
            'course_point' => 'required',
            'installment_1' => 'required',
            'installment_2' => 'required',
            // 'has_website' => 'required',

        ];
    }
}
