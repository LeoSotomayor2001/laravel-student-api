<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        $students=Student::all();
        if(count($students)==0){
            return response()->json([
                'message'=>'No hay estudiantes para mostrar'
            ],200);
        }
        return response()->json($students,200);
    }
    
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'name'=>'required|min:3|max:255',
            'email'=>'required|email|unique:students,email',
            'phone'=>'required|digits:11',
            'language'=>'required',
        ]);
        
        if($validator->fails()){
            $data=[
                'status'=>400,
                'errors'=>$validator->errors(),
                'message'=>'No se pudo registrar el estudiante'
            ];
            return response()->json($data,400);
        }
        $student=Student::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'language'=>$request->language
        ]);
        if(!$student){
            $data=[
                'status'=>500,
                'message'=>'No se pudo registrar el estudiante'
            ];
            return response()->json($data,500);
        }
        $data=[
            'status'=>200,
            'message'=>'Estudiante registrado'
        ];

        return response()->json($data,200);
    }
    public function show($id){
        $student=Student::find($id);

        if(is_null($student)){
            return response()->json([
                'message'=>'No se encontro el estudiante'
            ],404);
        }
        $data=[
            'status'=>200,
            'student'=>$student,
        ];
        return response()->json($data,200);
    }
    public function update(Request $request,$id){
        $student=Student::find($id);
        if(is_null($student)){
            return response()->json([
                'message'=>'No se encontro el estudiante'
            ],404);
        }
        
        $validator=Validator::make($request->all(),[
            'name'=>'required|min:3|max:255',
            'email'=>'required|email|unique:students,email',
            'phone'=>'required|digits:11',
            'language'=>'required',
        ]);
        
        if($validator->fails()){
            $data=[
                'status'=>400,
                'errors'=>$validator->errors(),
                'message'=>'No se pudo registrar el estudiante'
            ];
            return response()->json($data,400);
        }
        $student->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'language'=>$request->language
        ]);

        return response()->json([
            'status'=>200,
            'message'=>'Estudiante actualizado'
        ]);
    }
    public function updatePartial(Request $request,$id){
        $student=Student::find($id);
        if(is_null($student)){
            return response()->json([
                'message'=>'No se encontro el estudiante'
            ],404);
        }
        $validator=Validator::make($request->all(),[
            'name'=>'min:3|max:255',
            'email'=>'email|unique:students,email',
            'phone'=>'digits:11',
        ]);
        if($validator->fails()){
            $data=[
                'status'=>400,
                'errors'=>$validator->errors(),
                'message'=>'No se pudo registrar el estudiante'
            ];
            return response()->json($data,400); 
        }
        if(isset($request->name)){
            $student->name=$request->name;  
        }
        if(isset($request->email)){
            $student->email=$request->email;
        }
        if(isset($request->phone)){
            $student->phone=$request->phone;
        }
        if(isset($request->language)){
            $student->language=$request->language;
        }
        $student->save();
        return response()->json([
            'status'=>200,
            'message'=>'Estudiante actualizado'

        ]);
       
    }
    public function destroy($id){
        $student=Student::find($id);
        if(is_null($student)){
            return response()->json([
                'message'=>'No se encontro el estudiante'
            ],404);
        }
        $student->delete();
        return response()->json([
            'status'=>200,
            'message'=>'Estudiante eliminado'
        ]);
    }
}