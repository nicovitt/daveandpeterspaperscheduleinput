<?php

namespace VittITServices\humhub\modules\daveandpeterspaperscheduleinput\controllers;

use Yii;
use \Datetime;
use Exception;
use humhub\modules\tasks\models\Task;
use humhub\modules\tasks\models\forms\TaskForm;
use humhub\modules\content\components\ContentContainerController;
use VittITServices\humhub\modules\daveandpeterspaperscheduleinput\helpers\Url;
use VittITServices\humhub\modules\daveandpeterspaperscheduleinput\models\PaperInputModel;
use VittITServices\humhub\modules\daveandpeterspaperscheduleinput\helpers\APITaskCollection;

require Yii::$app->getModule('daveandpeterspaperscheduleinput')->basePath . "/helpers/Task.class.php";

class SpaceController extends ContentContainerController
{
    /**
     * Renders the space view for the module
     * 
     * @return string
     */
    public function actionIndex()
    {
        // Don't know what this is for...
        $error = false;

        // Make a new model
        $model = new PaperInputModel();

        // Check if there is a POST-request to the view
        if (Yii::$app->request->isPost) {
            // Fill the model with data from post
            if ($model->load(Yii::$app->request->post())) {

                // Check if there are some errors in the model
                if (new DateTime($model->startdate) >= new DateTime($model->enddate)) {
                    $model->message = "Really? You needed it yesterday? Time-management my friend, time-management...";
                    $model->success = false;
                }
                if (empty(Yii::$app->request->post('fileList'))) {
                    $model->message = "The paper file, you should select, my young Padawan.";
                    $model->success = false;
                }
                if (!$model->upload()) {
                    $model->message = "What's that for a file? I could not upload it...";
                    $model->success = false;
                }
            } else {
                $model->message = "There is something wrong with the data... Bet, you inserted some s**t????";
                $model->success = false;
            }

            // Send the task to the API to create a new task if the model has no errors
            if ($model->success) {       
                $result = $this->posttoapi($model);
                try {
                    if (property_exists($result, 'id')) {
                        // Attach the file to the newly created task
                        $task = Task::findOne(['id' => $result->id]);
                        $task->fileManager->attach(Yii::$app->request->post('fileList'));

                        // Make a new model if everything was fine and return this to the view
                        $model = new PaperInputModel();
                        $model->message = "Ther' ya go... I sent them your paper. Hopefully they find it as interesting as I did! #irony #iamjustamachine #nohate -.-";
                    } else {
                        // If the property "id" does not exist then there is an error in posting the task to the API
                        $model->message = "We got something suspicious back. Here is the message:<br />" . json_encode($result);
                        $model->success = false;
                    }
                } catch (Exception $e) {
                    //Some other error in the call to the API
                    $model->message = "Something went horribly wrong... Please inform an admin. Here is the message:<br />" . json_encode($result);
                    $model->success = false;
                }
            }
        }
        
        //Return the view with the model
        return $this->render('index', [
            'contentContainer' => $this->contentContainer,
            'baseurl' => $this->contentContainer->getUrl(),
            'model' => $model
        ]);
    }

    private function posttoapi(PaperInputModel $model)
    {
        // Data should be send in the following format:
        // "start_date": "2021-05-03 01:02:03",
        // "start_time": "10:00 AM",
        // "end_date": "2021-05-04 04:05:06",
        // "end_time": "7:30 PM",

        // Build the URL for the API-endpoint
        $urlhelper = new Url();
        $url = $urlhelper->domainname() . "/api/v1/tasks/container/" . $this->contentContainer->contentcontainer_id;

        //Fill in the fields in the task
        $description = 'Venue: ' . $model->venue . PHP_EOL . PHP_EOL . 'Authors: ' . $model->authors . PHP_EOL . PHP_EOL . $model->description;
        $data = new APITaskCollection();
        $data->Task->title = $model->title;
        $data->Task->description = $description;
        $data->TaskForm->end_date = date_format(date_create_from_format("d.m.Y", $model->enddate), "Y-m-d H:i:s");
        
        try {
            // Get the contents of the JSON file 
            $credjson = file_get_contents(Yii::$app->getModule('daveandpeterspaperscheduleinput')->basePath . "/resources/credentials.json");
            // Convert to array 
            $credarray = json_decode($credjson, true);
        } catch (Exception $ex) {
            return json_decode("The file with the credentials is missing.");
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $credarray['username'] . ':' . $credarray['password']);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result);
    }
}
